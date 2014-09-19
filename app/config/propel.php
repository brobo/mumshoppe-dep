<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

define('DB_HOST', 'localhost');
define('DB_NAME', 'mums');
define('DB_USER', 'mumshoppe');
define('DB_PASSWORD', 'RSdVhBbxvLFx4CnZ');

require_once 'app/vendor/autoload.php';
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionManagerSingle;
$serviceContainer = Propel::getServiceContainer();
$serviceContainer->setAdapterClass('mums', 'mysql');
$manager = new ConnectionManagerSingle();
$manager = new ConnectionManagerSingle();
$manager->setConfiguration(array (
	'dsn'		=>	'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
	'user'		=>	DB_USER,
	'password'	=>	DB_PASSWORD
));
$serviceContainer->setConnectionManager('mums', $manager);
