<?php

require_once 'app/vendor/autoload.php';
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionManagerSingle;
$serviceContainer = Propel::getServiceContainer();
$serviceContainer->setAdapterClass('mums', 'mysql');
$manager = new ConnectionManagerSingle();
$manager = new ConnectionManagerSingle();
$manager->setConfiguration(array (
	'dsn'		=>	'mysql:host=localhost;dbname=mums',
	'user'		=>	'root',
	'password'	=>	''
));
$serviceContainer->setConnectionManager('mums', $manager);