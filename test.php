<?php

$success = mail("blacksmithgu@gmail.com", "Mercury test mail", "If you can read this, everything was fine!");

if($success)
	echo "Mail was sent successfully.";
else
	echo "Mail failed to send.";
?>