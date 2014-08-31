<?php

	$app->get('/api/accentbow', function() {
		$bows = AccentBowQuery::create()->find();
		
		if (!$bows) return;
		
		echo json_encode($bows->toArray());
	});

	$app->get('/api/accentbow/:id', function($id) {
		$bow = AccentBowQuery::create()->findPK($id);
		
		if (!$bow) return;

		echo $bow->toJson();
	});

	$app->put('/api/accentbow/:id', function($id) use ($app) {

		$bow = AccentBowQuery::create()->findPK($id);

		if (!$bow) return;

		foreach ($app->request->put() as $key => $value) {
			$bow->setByName($key, $value);
		}

		$bow->save();

		echo $bow->toJson();
	});

	$app->post('/api/accentbow', function() use ($app) {
		$bow = new AccentBow();

		foreach ($app->request->post() as $key => $value) {
			$bow->setByName($key, $value);
		}

		$bow->save();

		echo $bow->toJson();
	});

	$app->delete('/api/accentbow/:id', function($id) {
		$bow = AccentBowQuery::create()->findPK($id);

		if (!$bow) return;

		$bow->delete();
		
		echo $bow->toJson();
	});

	$app->post('/api/accentbow/:id/image', function($id) {
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
