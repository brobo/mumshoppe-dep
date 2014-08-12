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
?>