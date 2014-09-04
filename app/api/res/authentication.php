<?php
	function auth_fail($message) {
		header('HTTP/1.0 401 Unauthorized');
		echo 'You failed to provide a valid token for that request.' . "\n";
		echo $message . "\n";
		
		exit;		
	}
	
	function getToken() {
		if(isset($app->token))
			return $app->token;
		return null;
	}
	
	function isVolunteer() {
		return getToken()["Type"] === "Volunteer";
	}
	
	function isCustomer() {
		return getToken()["Type"] === "Customer";
	}

	function auth_precheck() {
		$app = \Slim\Slim::getInstance();
		$auth = $app->request->headers->get('Authentication');
		if (!$auth)
			auth_fail('No token provided.');
		
		$token = json_decode(json_encode(JWT::decode($auth, JWTKEY)), true);
		if (!$token)
			auth_fail('Token is invalid or corrupt.');
		
		$app->token = $token;
		
		return $token;
	}
	
	// Only lets in volunteers with the provided rights.
	function auth_volunteer($right) {
		return function() use ($right) {
			$app = \Slim\Slim::getInstance();
			$token = auth_precheck();
			
			if($token["Type"] != "Volunteer")
				auth_fail("You must be a volunteer to use this API call.");
			
			$volunteer = VolunteerQuery::create()->findPK($token['Id']);
			// if (!$volunteer) auth_fail('Token is not for a valid user.');
			// $expiration = new DateTime($token['expiration']);
			
			// if ($expiration !== $volunteer->getTokenExpiration())
			// 	auth_fail('Expirations do not match.');
			 
			// if (new DateTime() > $volunteer->getTokenExpiration())
			// 	auth_fail('Your token is expired.');

			if ($right === -1) return;
			
			if (!VolunteerRights::HasRight($token['Rights'], $right))
					auth_fail('You do not have that right.');
		};
	}
	
	// Allows all customers and then volunteers with provided rights.
	function auth_all($rights) {
		return function() use ($rights) {
			$token = auth_precheck();
			
			switch($token["Type"]) {
				case "Customer":
					break;
				case "Volunteer":
					auth_volunteer($rights);
					break;
			}
		};
	}

	function auth_loggedin() {
		return function() {
			$token = auth_precheck();
		};
	}

	function auth_customer() {
		return function() {
			$token = auth_precheck();
			if ($token['Type'] != 'Customer')
				auth_fail('You do not have that right.');
		};
	}
?>