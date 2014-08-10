<?php
	require_once 'vendor/autoload.php';
	require_once 'app/config/propel.php';

	$app = new \Slim\Slim();

	$app->get('/hello/:name', function($name) {
		echo "Hello, $name";
	});

	require('app/api/garnish.php');

	$app->run();
?>
