<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/
	
	$app->get('/api/backing', auth_all(VolunteerRights::ConfigureItems), function() use ($app) {
		$backings = BackingQuery::create()->find();

		if (!$backings) return;

		$encodeBacking = function($backing) {
			return $backing->getFull();
		};

		echo json_encode(array_map($encodeBacking, $backings->getData()));
	});

	$app->get('/api/backing/:id', auth_all(VolunteerRights::ConfigureItems), function($id) {
		$backing = BackingQuery::create()->findPK($id);

		if (!$backing) return;

		echo json_encode($backing->getFull());
	});

	$app->put('/api/backing/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($app) {
		$backing = BackingQuery::create()->findPK($id);

		if (!$backing) return;

		foreach ($app->request->put() as $key => $value) {
			try {
				$backing->setByName($key, $value);
			} catch (Exception $ex) {}
			
		}

		$backing->save();

		echo json_encode($backing->getFull());
	});

	$app->post('/api/backing', auth_volunteer(VolunteerRights::ConfigureItems), function() use ($app) {
		$backing = new Backing();

		foreach ($app->request->post() as $key => $value) {
			$backing->setByName($key, $value);
		}

		$backing->save();

		echo json_encode($backing->getFull());
	});

	$app->delete('/api/backing/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$backing = BackingQuery::create()->findPK($id);

		if (!$backing) return;

		$backing->delete();

		echo json_encode($backing->getFull());
	});

	$app->post('/api/backing/:id/image', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($confirmUpload) {
		$backing = BackingQuery::create()->findPK($id);
		if (!$backing) return;

		if (($reason = $confirmUpload($_FILES['image'])) !== null) {
			echo json_encode(array('success' => 'false', 'reason' => $reason));
		}

		$filename = uniqid('backing', true);
		move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $filename);
		$backing->setImage($filename);

		$backing->save();
		echo json_encode(array('message' => 'Success!'));
	});

	$app->get('/api/backing/:id/image', function($id) use ($app) {
		$app->response->header('Content-Type', 'content-type: image/jpg');

		$backing = BackingQuery::create()->findPK($id);
		if (!$backing) return;

		$filename = UPLOAD_DIR . $backing->getImage();
		$fp = fopen($filename, 'rb');

		$res = $app->response();
		if ($fp !== null) {
			$content = stream_get_contents($fp, -1, 0);
			$res->header('Content-Type', 'content-type: ' . filetype($filename));
			echo $content;
		}
	});
?>
