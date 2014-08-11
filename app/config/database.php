<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'mumshoppe');
define('DB_PASSWORD', 'pCr38i@AjhHW2j85!i2T1Lh2Pn&#Vpil');
define('DB_NAME', 'mums');

/*$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}*/

$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

?>