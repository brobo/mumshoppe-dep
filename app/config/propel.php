<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

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