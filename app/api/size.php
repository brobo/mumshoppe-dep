<?php
	
	$app->get('/api/size', function() use ($app) {
		$sizes = SizeQuery::create()->find();

		if (!$sizes) return;

		echo json_encode($sizes->toArray());
	});

	$app->get('/api/size/:id', function($id) {
		$size = SizeQuery::create()->findPK($id);

		if (!$size) return;

		echo $size->toJson();
	});

	$app->put('/api/size/:id', function($id) use ($app) {
		$size = SizeQuery::create()->findPK($id);

		if (!$size) return;

		foreach ($app->request->put() as $key => $value) {
			$size->setByName($key, $value);
		}

		$size->save();

		echo $size->toJson();
	});

	$app->post('/api/size', function() use ($app) {
		$size = new Size();

		foreach ($app->request->post() as $key => $value) {
			$size->setByName($key, $value);
		}

		$size->save();

		echo $size->toJson();
	});

	$app->delete('/api/size/:id', function($id) {
		$size = SizeQuery::create()->findPK($id);

		if (!$size) return;

		$size->delete();

		echo $size->toJson();
	});

	$app->post('/api/size/:id/image', function($id) {
		$size = SizeQuery::create()->findPK($id);
		if (!$size) return;

		$content = file_get_contents($_FILES['image']['tmp_name']);
		$size->setImage($content);
		$size->setImageMime($_FILES['image']['type']);

		$size->save();
		echo json_encode(array('message' => 'Success!'));
	});

	$app->get('/api/size/:id/image', function($id) use ($app) {
		$app->response->header('Content-Type', 'content-type: image/jpg');

		$size = SizeQuery::create()->findPK($id);
		if (!$size) return;

		$fp = $size->getImage();

		$res = $app->response();
		if ($fp !== null) {
			$content = stream_get_contents($fp, -1, 0);
			$res->header('Content-Type', 'content-type: ' . $size->getImageMime());
			echo $content;
		}
	});
?>
