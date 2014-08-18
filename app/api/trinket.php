<?php

	$app->get('/api/trinket', function() {
		$trinkets = TrinketQuery::create()->find();
		
		if (!$trinkets) return;
		
		echo json_encode($trinkets->toArray());
	});

	$app->get('/api/trinket/:id', function($id) {
		$trinket = TrinketQuery::create()->findPK($id);
		
		if (!$trinket) return;

		echo $trinket->toJson();
	});

	$app->put('/api/trinket/:id', function($id) use ($app) {

		$trinket = TrinketQuery::create()->findPK($id);

		if (!$trinket) return;

		foreach ($app->request->put() as $key => $value) {
			$trinket->setByName($key, $value);
		}

		$trinket->save();

		echo $trinket->toJson();
	});

	$app->post('/api/trinket', function() use ($app) {
		$trinket = new Trinket();

		foreach ($app->request->post() as $key => $value) {
			$trinket->setByName($key, $value);
		}

		$trinket->save();

		echo $trinket->toJson();
	});

	$app->delete('/api/trinket/:id', function($id) {
		$trinket = TrinketQuery::create()->findPK($id);

		if (!$trinket) return;

		$trinket->delete();
		
		echo $trinket->toJson();
	});

?>
