<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/
	 
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}

	$app->post("/api/recover", function() use ($app) {
		$email = $app->request->post("Email");
		
		$user = CustomerQuery::create()->filterByEmail($email)->findOne();
		if(!$user) return;
		
		$randString = generateRandomString(15);
		$time = new DateTime();
		$time->modify("+60 minutes");
		
		$recov = new PasswordRecovery();
		$recov->setCustomer($user);
		$recov->setKeyword($randString);
		$recov->setExpiration($time);
		
		$recov->save();
		
		$succ = mail($email, "MumShoppe Password Recovery", "Your recovery link: http://localhost/mums/mumshoppe#/home/recoverpassword/" . $randString);

		echo json_encode(array('success' => $succ));
	});
	
	$app->post("/api/recover/:keyword", function($keyword) use ($app) {
		$passRec = PasswordRecoveryQuery::create()->filterByKeyword($keyword)->findOne();
		if(!$passRec) {
			echo json_encode(array('success' => false, 'reason' => 'Unknown recovery key.'));
			return;
		}
		
		$endTime = $passRec->getExpiration();
		$nowTime = new DateTime();
		
		if($nowTime > $endTime) {
			$passRec->delete();
			echo json_encode(array('success' => false, 'reason' => 'Expired key.'));
			return;
		}
		
		$pass = $app->request->post("Password");
		
		$user = $passRec->getCustomer();
		$user->setPassword(password_hash($pass, PASSWORD_BCRYPT));
		$user->save();
		
		$passRec->delete();

		echo json_encode(array('success' => true));
	});
?>
