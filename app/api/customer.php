<?php
	$app->post('/api/customer', function() use ($app) {
		$customer = new Customer();

		$customer->setName($app->request->post('Name'));
		$customer->setEmail($app->request->post('Email'));
		$customer->setPassword(password_hash($app->request->post('Password'), PASSWORD_BCRYPT));
		$customer->setPhone($app->request->post('Phone'));

		$customer->save();

		$res = array(
			"Name" => $customer->getName(),
			"Email" => $customer->getEmail(),
			"Phone" => $customer->getPhone());
		echo json_encode($res);
	});

	$app->post('/api/customer/login', function() use ($app) {
		$email = $app->request->post('Email');
		$password = $app->request->post('Password');

		echo $email;

		$customer = CustomerQuery::create()->filterByEmail($email)->findOne();
		if (password_verify($password, $customer->getPassword())) {
			echo json_encode('Successfully logged in.');
		} else {
			$app->response->setStatus(401);
		}
	});

	$app->post('/api/customer/verify', function() use ($app) {
		$customers = CustomerQuery::create()->filterByEmail($app->request->post('Email'))->count();
		if ($customers > 0) {
			echo json_encode(array('valid' => false));	
		} else {
			echo json_encode(array('valid' => true));
		}
	});
?>