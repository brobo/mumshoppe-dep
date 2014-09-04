<?php

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

		echo json_encode($bow->toFull());
	});

	$app->put('/api/accentbow/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($app) {

		$bow = AccentBowQuery::create()->findPK($id);

		if (!$bow) return;

		foreach ($app->request->put() as $key => $value) {
			$bow->setByName($key, $value);
		}

		$bow->save();

		echo json_encode($bow->toFull());
	});

	$app->post('/api/accentbow', auth_volunteer(VolunteerRights::ConfigureItems), function() use ($app) {
		$bow = new AccentBow();

		foreach ($app->request->post() as $key => $value) {
			$bow->setByName($key, $value);
		}

		$bow->save();

		echo json_encode($bow->toFull());
	});

	$app->delete('/api/accentbow/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$bow = AccentBowQuery::create()->findPK($id);

		if (!$bow) return;

		$bow->delete();
		
		echo json_encode($bow->toFull());
	});

	$app->post('/api/accentbow/:id/image', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$accentbow = AccentBowQuery::create()->findPK($id);
		if (!$accentbow) return;

		$content = file_get_contents($_FILES['image']['tmp_name']);
		$accentbow->setImage($content);
		$accentbow->setImageMime($_FILES['image']['type']);

		$accentbow->save();
		echo json_encode(array('message' => 'Success!'));
	});

	$app->get('/api/accentbow/:id/image', function($id) use ($app) {
		$app->response->header('Content-Type', 'content-type: image/jpg');

		$accentbow = AccentBowQuery::create()->findPK($id);
		if (!$accentbow) return;

		$fp = $accentbow->getImage();

		$res = $app->response();
		if ($fp !== null) {
			$content = stream_get_contents($fp, -1, 0);
			$res->header('Content-Type', 'content-type: ' . $accentbow->getImageMime());
			echo $content;
		}
	});

?>
