<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/
	 
	define('PHPASS_COST', 10);

	use Hautelook\Phpass\PasswordHash;

	$passwordHasher = new PasswordHash(PHPASS_COST, false);
?>