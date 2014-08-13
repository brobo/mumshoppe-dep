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
	})
?>
