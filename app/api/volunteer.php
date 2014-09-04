<?php

	$app->get('/api/volunteer', auth_volunteer(VolunteerRights::NO_RIGHTS), function() {
		$volunteers = VolunteerQuery::create()->find();

		$encodeVolunteer = function($volunteer) {
			return array(
				"Id" => $volunteer->getId(),
				"Name" => $volunteer->getName(),
				"Email" => $volunteer->getEmail(),
				"Phone" => $volunteer->getPhone(),
				"Rights" => $volunteer->getRights()
			);
		};

		echo json_encode(array_map($encodeVolunteer, $volunteers->getData()));
	});

	$app->put('/api/volunteer/:volunteerId', auth_volunteer(VolunteerRights::CreateVolunteer), function($volunteerId) use ($app) {
		$volunteer = VolunteerQuery::create()->findPK($volunteerId);
		if (!$volunteer) return;

		if ($app->request->put('Name')) {
			echo $app->request->put('Name');
			$volunteer->setName($app->request->put('Name'));
		}
		if ($app->request->put('Phone')) {
			$volunteer->setPhone($app->request->put('Phone'));
		}

		$volunteer->save();

		echo json_encode(array(
			"message" => "Success!"
		));
	});

	$app->post('/api/volunteer', auth_volunteer(VolunteerRights::CreateVolunteer), function() use ($app) {
		$volunteer = new Volunteer();

		$volunteer->setName($app->request->post('Name'));
		$volunteer->setEmail($app->request->post('Email'));
		$volunteer->setPassword(password_hash($app->request->post('Password'), PASSWORD_BCRYPT));
		$volunteer->setPhone($app->request->post('Phone'));

		$volunteer->save();

		$res = array(
			"Id" => $volunteer->getId(),
			"Name" => $volunteer->getName(),
			"Email" => $volunteer->getEmail(),
			"Phone" => $volunteer->getPhone(),
			"Rights" => $volunteer->getRights()
		);
		echo json_encode($res);
	});

	$app->post('/api/volunteer/login', auth_volunteer(VolunteerRights::CreateVolunteer), function() use ($app) {
		$email = $app->request->post('Email');
		$password = $app->request->post('Password');

		$volunteer = VolunteerQuery::create()->filterByEmail($email)->findOne();
		if (password_verify($password, $volunteer->getPassword())) {
			$token = array(
				'Email' => $volunteer->getEmail(),
				'Id' => $volunteer->getId(),
				'Type' => 'Volunteer',
				'Rights' => $volunteer->getRights()
			);
			$jwt = JWT::encode($token, JWTKEY);
			echo json_encode(array(
				'Name' => $volunteer->getName(),
				'jwt' => $jwt
			));
		} else {
			$app->response->setStatus(401);
		}
	});

	$app->post('/api/volunteer/verify', auth_volunteer(VolunteerRights::CreateVolunteer), function() use ($app) {
		$volunteers = VolunteerQuery::create()->filterByEmail($app->request->post('Email'))->count();
		if ($volunteers > 0) {
			echo json_encode(array('valid' => false));	
		} else {
			echo json_encode(array('valid' => true));
		}
	});

	$app->post('/api/volunteer/:id/rights', auth_volunteer(VolunteerRights::ChangeVolunteerPerms), function($id) use ($app) {
		$volunteer = VolunteerQuery::create()->findPK($id);
		if (!$volunteer) return;

		$rights = $app->request->post('Rights');
		if ($rights === null) return;

		$volunteer->setRights($rights);
		$volunteer->save();

		echo json_encode(array('success' => true));
	});
?>
