<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

	$app->get('/api/accentbow', auth_all(VolunteerRights::ConfigureItems), function() {
		$bows = AccentBowQuery::create()->find();
		
		if (!$bows) return;

		$encodeBow = function($bow) {
			return $bow->getFull();
		};
		
		echo json_encode(array_map($encodeBow, $bows->getData()));
	});

	$app->get('/api/accentbow/:id', auth_all(VolunteerRights::ConfigureItems), function($id) {
		$bow = AccentBowQuery::create()->findPK($id);
		
		if (!$bow) return;

		echo json_encode($bow->getFull());
	});

	$app->put('/api/accentbow/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($app) {

		$bow = AccentBowQuery::create()->findPK($id);

		if (!$bow) return;

		foreach ($app->request->put() as $key => $value) {
			try {
				$bow->setByName($key, $value);
			} catch (Exception $ex) {}
		}

		$bow->save();

		echo json_encode($bow->getFull());
	});

	$app->post('/api/accentbow', auth_volunteer(VolunteerRights::ConfigureItems), function() use ($app) {
		$bow = new AccentBow();

		foreach ($app->request->post() as $key => $value) {
			$bow->setByName($key, $value);
		}

		$bow->save();

		echo json_encode($bow->getFull());
	});

	$app->delete('/api/accentbow/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$bow = AccentBowQuery::create()->findPK($id);

		if (!$bow) return;

		$bow->delete();
		
		echo json_encode($bow->getFull());
	});

	$app->post('/api/accentbow/:id/image', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($confirmUpload) {
		$accentbow = AccentBowQuery::create()->findPK($id);
		if (!$accentbow) return;

		if (($reason = $confirmUpload($_FILES['image'])) !== null) {
			echo json_encode(array('success' => 'false', 'reason' => $reason));
		}

		$filename = uniqid('accentbow', true);
		move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $filename);
		$accentbow->setImage($filename);

		$accentbow->save();
		echo json_encode(array('message' => 'Success!'));
	});

	$app->get('/api/accentbow/:id/image', function($id) use ($app) {
		$app->response->header('Content-Type', 'content-type: image/jpg');

		$accentbow = AccentBowQuery::create()->findPK($id);
		if (!$accentbow) return;

		$filename = UPLOAD_DIR . $accentbow->getImage();
		$fp = fopen($filename, 'rb');

		$res = $app->response();
		if ($fp !== null) {
			$content = stream_get_contents($fp, -1, 0);
			$res->header('Content-Type', 'content-type: ' . filetype($filename));
			echo $content;
		}
	});

?>
