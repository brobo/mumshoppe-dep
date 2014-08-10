<?php

	$app->get('/api/garnish', function() {
		$garnishes = GarnishQuery::create()->find();
		
		if (!$garnishes) return;
		
		echo $garnishes->toJson();
	});

	$app->get('/api/garnish/:id', function($id) {
		$garnish = GarnishQuery::create()->findPK($id);
		
		if (!$garnish) return;

		echo $garnish->toJson();
	});

	$app->put('/api/garnish/:id', function($id) use ($app) {
		$garnish = GarnishQuery::create()->findPK($id);

		if (!$garnish) return;

		foreach ($app->request->put() as $key => $value) {
			$garnish->setByName($key, $value);
		}

		$garnish->save();
	});

	$app->post('/api/garnish', function() use ($app) {
		$garnish = new Garnish();

		foreach ($app->request->post() as $key => $value) {
			$garnish->setByName($key, $value);
		}

		$garnish->save();

		echo $garnish->toJson();
	});

	$app->delete('/api/garnish/:id', function($id) {
		$garnish = GarnishQuery::create()->findPK($id);

		if (!$garnish) return;

		$garnish->delete();
		
		echo $garnish->toJson();
	})

?>
