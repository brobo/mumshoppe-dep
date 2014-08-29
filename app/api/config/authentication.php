<?php

	$failAuthentication = function($message) {
		header('HTTP/1.0 401 Unauthorized');
		echo 'You failed to provide a valid token for that request.' . "\n";
		echo $message . "\n";

		exit;
	}

	 $authVolunteer() = function() {
	 	return function() {
	 		$app = \Slim\Slim::getInstance();
	 		$auth = $app->request->headers->get('Authentication');
	 		if (!$auth) {
	 			$failAuthentication('No token provided.');
	 		}

	 		$token = JWT::decode($auth, JWTKEY);
	 		if (!$token) {
	 			$failAuthentication('Token is invalid or corrupt.');
	 		}

	 		if ($token['Type'] != 'Volunteer')
	 			$failAuthentication('You provided the wrong type of token.');

	 		$volunteer = VolunteerQuery::create()->findPK($token['Id']);
	 		if (!$volunteer) $failAuthentication('Token is not for a valid user.');
	 		$expiration = new DateTime($token['expiration']);

	 		if ($expiration !== $volunteer->getTokenExpiration())
	 			$failAuthentication('Expirations do not match.');
	 	
	 		if (new DateTime() > $volunteer->getTokenExpiration())
	 			$failAuthentication('Your token is expired.');
	 	}
	}
?>