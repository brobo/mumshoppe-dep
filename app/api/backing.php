<?php
	
	$app->get('/api/backing', function() use ($app) {
		$backings = BackingQuery::create()->find();

		if (!$backings) return;

		echo json_encode($backings->toArray());
	});

	$app->get('/api/backing/:id', function($id) {
		$backing = BackingQuery::create()->findPK($id);

		if (!$backing) return;

		echo $backing->toJson();
	});

	$app->put('/api/backing/:id', function($id) use ($app) {
		$backing = BackingQuery::create()->findPK($id);

		if (!$backing) return;

		foreach ($app->request->put() as $key => $value) {
			$backing->setByName($key, $value);
		}

		$backing->save();

		echo $backing->toJson();
	});

	$app->post('/api/backing', function() use ($app) {
		$backing = new Backing();

		foreach ($app->request->post() as $key => $value) {
			$backing->setByName($key, $value);
		}

		$backing->save();

		echo $backing->toJson();
	});

	$app->delete('/api/backing/:id', function($id) {
		$backing = BackingQuery::create()->findPK($id);

		if (!$backing) return;

		$backing->delete();

		echo $backing->toJson();
	})
?>
