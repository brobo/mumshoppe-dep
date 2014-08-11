<?php
	require_once 'app/vendor/autoload.php';
	require_once 'app/config/propel.php';

	$app = new \Slim\Slim();

	$app->config(array(
		'templates.path' => './public'
	));

	$app->get('/hello/:name', function($name) {
		echo "Hello, $name";
	});

	$app->get('/volunteer', function() use ($app) {
		$app->render('volunteer.html');
	});

	require('app/api/trinket.php');

	$app->run();
?>
