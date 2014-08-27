<?php
	
	$app->get('/api/pay/deposit/:mumId', function($mumId) use ($app, $paypalSdkConfig) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;

		$oauthCredential = new PayPal\Auth\OAuthTokenCredential(CLIENT_ID, SECRET);
		$accessToken = $oauthCredential->getAccessToken($paypalSdkConfig);

		// $apiContext = new PayPal\Rest\ApiContext("Bearer $accessToken", 'Request' . time());
		$apiContext = new PayPal\Rest\ApiContext($oauthCredential);
		$apiContext->setConfig($paypalSdkConfig);

		$payer = new PayPal\Api\Payer();
		$payer->setPayment_method('paypal');

		$amount = new PayPal\Api\Amount();
		$amount->setCurrency("USD");
		$amount->setTotal($mum->getFull()['TotalPrice']);

		$transaction = new PayPal\Api\Transaction();
		$transaction->setDescription('Ordering a mum');
		$transaction->setAmount($amount);

		$redirectUrls = new PayPal\Api\RedirectUrls();
		$redirectUrls->setReturn_url("https://devtools-paypal.com/guide/pay_paypal/php?success=true");
		$redirectUrls->setCancel_url("https://devtools-paypal.com/guide/pay_paypal/php?cancel=true");

		$payment = new PayPal\Api\Payment();
		$payment->setIntent("sale");
		$payment->setPayer($payer);
		$payment->setRedirect_urls($redirectUrls);
		$payment->setTransactions(array($transaction));

		$response = $payment->create($apiContext);

		print_r($response);
	});

?>