<?php
	
	$app->get('/api/pay/deposit/:mumId', function($mumId) use ($app, $paypalSdkConfig) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;

		$oauthCredential = new PayPal\Auth\OAuthTokenCredential(CLIENT_ID, SECRET);
		$accessToken = $oauthCredential->getAccessToken($paypalSdkConfig);

		$_SESSION['access-token'] = $oauthCredential;

		$apiContext = new PayPal\Rest\ApiContext($oauthCredential);
		$apiContext->setConfig($paypalSdkConfig);

		$payer = new PayPal\Api\Payer();
		$payer->setPayment_method('paypal');

		$amount = new PayPal\Api\Amount();
		$amount->setCurrency("USD");
		$amount->setTotal(25);
		// $amount->setTotal($mum->getFull()['TotalPrice']);

		$transaction = new PayPal\Api\Transaction();
		$transaction->setDescription('Ordering a mum');
		$transaction->setAmount($amount);

		$redirectUrls = new PayPal\Api\RedirectUrls();
		$redirectUrls->setReturn_url(URL_BASE . '/mumshoppe#/create/' . $mumId . '/finalize?success=true');
		$redirectUrls->setCancel_url(URL_BASE . '/mumshoppe#/mums?success=false');
		// $redirectUrls->setReturn_url("https://devtools-paypal.com/guide/pay_paypal/php?success=true");
		// $redirectUrls->setCancel_url("https://devtools-paypal.com/guide/pay_paypal/php?cancel=true");

		$payment = new PayPal\Api\Payment();
		$payment->setIntent("sale");
		$payment->setPayer($payer);
		$payment->setRedirect_urls($redirectUrls);
		$payment->setTransactions(array($transaction));

		$response = $payment->create($apiContext);

		$_SESSION['payment-id'] = $response->getId();

		$found = false;

		foreach ($response->getLinks() as $link) {
			if ($link->getRel() == 'approval_url') {
				$found = true;
				echo json_encode(array('location' => $link->getHref()));
			}
		}
		if (!$found) {
			header('HTTP/1.1 500 Internal Server Error');
		}
	});

	$app->post('/api/pay/deposit/:mumId', function($mumId) use ($app, $paypalSdkConfig) {
		if (!isset($_SESSION['payment-id'])) return;
		if (!isset($_SESSION['access-token'])) return;

		$payer_id = $app->request->post('PayerId');
		if (!$payer_id) {
			echo "No payer id";
			echo json_encode($app->request->post());
			return;
		}
		
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) {
			echo "No mum";
			return;
		}

		$apiContext = new PayPal\Rest\ApiContext($_SESSION['access-token']);
		$apiContext->setConfig($paypalSdkConfig);

		$payment = new PayPal\Api\Payment();
		$payment->setId($_SESSION['payment-id']);
		$execution = new PayPal\Api\PaymentExecution();
		$execution->setPayer_id($payer_id);
		try {
			$payment->execute($execution, $apiContext);
		} catch (Exception $ex) {
			echo json_encode(array('success' => false));
			return;
		}

		$mum->setStatusId(2);
		$mum->setOrderDate(new DateTime('now'));
		$mum->setDepositSaleId($_SESSION['payment-id']);
		$mum->save();

		echo json_encode(array('success' => true));
	});

?>
