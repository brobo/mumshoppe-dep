<?php

require_once 'vendor/autoload.php';
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionManagerSingle;
$serviceContainer = Propel::getServiceContainer();
$serviceContainer->setAdapterClass('mums', 'mysql');
$manager = new ConnectionManagerSingle();
$manager = new ConnectionManagerSingle();
$manager->setConfiguration(array (
	'dsn'		=>	'mysql:host=localhost;dbname=mums',
	'user'		=>	'mumshoppe',
	'password'	=>	'pCr38i@AjhHW2j85!i2T1Lh2Pn&#Vpil'
));
$serviceContainer->setConnectionManager('mums', $manager);