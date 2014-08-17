<?php

	$app->get('/api/letter', function() {
		$letters = LetterQuery::create()->find();
		
		if (!$letters) return;
		
		echo json_encode($letters->toArray());
	});

	$app->get('/api/letter/:id', function($id) {
		$letter = LetterQuery::create()->findPK($id);
		
		if (!$letter) return;

		echo $letter->toJson();
	});

	$app->put('/api/letter/:id', function($id) use ($app) {

		$letter = LetterQuery::create()->findPK($id);

		if (!$letter) return;

		foreach ($app->request->put() as $key => $value) {
			$letter->setByName($key, $value);
		}

		$letter->save();

		echo $letter->toJson();
	});

	$app->post('/api/letter', function() use ($app) {
		$letter = new Letter();

		foreach ($app->request->post() as $key => $value) {
			$letter->setByName($key, $value);
		}

		$letter->save();

		echo $letter->toJson();
	});

	$app->delete('/api/letter/:id', function($id) {
		$letter = LetterQuery::create()->findPK($id);

		if (!$letter) return;

		$letter->delete();
		
		echo $letter->toJson();
	})

?>
