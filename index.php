<?php
	require('app/config/database.php');
	require 'vendor/autoload.php';

	$app = new \Slim\Slim();

	$app->get('/hello/:name', function($name) {
		echo "Hello, $name";
	});

	require('app/api/garnish.php');

	$app->run();
?>
