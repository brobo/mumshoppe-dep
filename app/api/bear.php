<?php

	$app->get('/api/bear', function() {
		$bears = BearQuery::create()->find();
		
		if (!$bears) return;
		
		echo json_encode($bears->toArray());
	});

	$app->get('/api/bear/:id', function($id) {
		$bear = BearQuery::create()->findPK($id);
		
		if (!$bear) return;

		echo $bear->toJson();
	});

	$app->put('/api/bear/:id', function($id) use ($app) {

		$bear = BearQuery::create()->findPK($id);

		if (!$bear) return;

		foreach ($app->request->put() as $key => $value) {
			$bear->setByName($key, $value);
		}

		$bear->save();

		echo $bear->toJson();
	});

	$app->post('/api/bear', function() use ($app) {
		$bear = new Bear();

		foreach ($app->request->post() as $key => $value) {
			$bear->setByName($key, $value);
		}

		$bear->save();

		echo $bear->toJson();
	});

	$app->delete('/api/bear/:id', function($id) {
		$bear = BearQuery::create()->findPK($id);

		if (!$bear) return;

		$bear->delete();
		
		echo $bear->toJson();
	});

?>
