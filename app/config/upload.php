<?php

	define('MAX_FILE_SIZE', 8000000);
	define('UPLOAD_DIR', 'images/');
	$ALLOWED_MIMES = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'];

	$confirmUpload = function($file) use ($ALLOWED_MIMES) {
		if ($file['size'] > MAX_FILE_SIZE)
			return 'File size is too large.';
		$found = false;
		for ($i=0; $i<count($ALLOWED_MIMES); $i++) {
			if ($file['type'] == $ALLOWED_MIMES[$i]) {
				$found = true;
				break;
			}
		}

		if (!$found) {
			return 'File type not allowed.';
		}

		return null;
	}

?>