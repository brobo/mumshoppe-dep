<?php
	require_once 'app/vendor/autoload.php';
	require_once 'app/config/propel.php';

	session_start();

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

	$app->get('/mumshoppe', function() use ($app) {
		$app->render('mumshoppe.html');
	});

	$app->get('/', function() use ($app) {
		$app->render('mumshoppe.html');
	});

	require('app/api/accentbow.php');
	require('app/api/customer.php');
	require('app/api/trinket.php');
	require('app/api/backing.php');
	require('app/api/customer.php');
	require('app/api/product.php');
	require('app/api/size.php');
	require('app/api/grade.php');
	require('app/api/mum.php');

	$app->run();
?>
