<?php
	
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

		echo json_encode($backing->toFull());
	});

	$app->put('/api/backing/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($app) {
		$backing = BackingQuery::create()->findPK($id);

		if (!$backing) return;

		foreach ($app->request->put() as $key => $value) {
			$backing->setByName($key, $value);
		}

		$backing->save();

		echo json_encode($backing->toFull());
	});

	$app->post('/api/backing', auth_volunteer(VolunteerRights::ConfigureItems), function() use ($app) {
		$backing = new Backing();

		foreach ($app->request->post() as $key => $value) {
			$backing->setByName($key, $value);
		}

		$backing->save();

		echo json_encode($backing->toFull());
	});

	$app->delete('/api/backing/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$backing = BackingQuery::create()->findPK($id);

		if (!$backing) return;

		$backing->delete();

		echo json_encode($backing->toFull());
	});

	$app->post('/api/backing/:id/image', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$backing = BackingQuery::create()->findPK($id);
		if (!$backing) return;

		$content = file_get_contents($_FILES['image']['tmp_name']);
		$backing->setImage($content);
		$backing->setImageMime($_FILES['image']['type']);

		$backing->save();
		echo json_encode(array('message' => 'Success!'));
	});

	$app->get('/api/backing/:id/image', auth_loggedin(), function($id) use ($app) {
		$app->response->header('Content-Type', 'content-type: image/jpg');

		$backing = BackingQuery::create()->findPK($id);
		if (!$backing) return;

		$fp = $backing->getImage();

		$res = $app->response();
		if ($fp !== null) {
			$content = stream_get_contents($fp, -1, 0);
			$res->header('Content-Type', 'content-type: ' . $backing->getImageMime());
			echo $content;
		}
	});
?>
