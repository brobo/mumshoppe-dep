<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/
	 
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

		$customer = CustomerQuery::create()->filterByEmail($email)->findOne();
		if (password_verify($password, $customer->getPassword())) {
			$token = array(
				'Email' => $customer->getEmail(),
				'Id' => $customer->getId(),
				'Type' => 'Customer'
			);
			$jwt = JWT::encode($token, JWTKEY);
			echo json_encode(array(
				'Name' => $customer->getName(),
				'jwt' => $jwt
			));
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
