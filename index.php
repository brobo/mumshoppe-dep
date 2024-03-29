<?php
	header('HTTP/1.1 500 Internal Server Error');

	use PayPal\Auth;

	require_once 'app/vendor/autoload.php';

	$app = new \Slim\Slim();
	
	require_once 'app/config/load.php';
	
	require_once 'app/config/jwt.php';
	require_once 'app/config/paypal.php';
	require_once 'app/config/propel.php';

	require_once 'app/api/res/authentication.php';
	require_once 'app/api/res/rights.php';

	session_start();

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
	require('app/api/accessory.php');
	require('app/api/backing.php');
	require('app/api/bear.php');
	require('app/api/category.php');
	require('app/api/customer.php');
	require('app/api/customer.php');
	require('app/api/grade.php');
	require('app/api/letter.php');
	require('app/api/mum.php');
	require('app/api/pay.php');
	require('app/api/product.php');
	require('app/api/size.php');
	require('app/api/volunteer.php');
	require('app/api/recovery.php');

	$app->run();
?>
