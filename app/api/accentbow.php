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
	})

?>
