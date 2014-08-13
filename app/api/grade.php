<?php
	
	$app->get('/api/grade', function() use ($app) {
		$grades = GradeQuery::create()->find();

		if (!$grades) return;

		echo json_encode($grades->toArray());
	});

	$app->get('/api/grade/:id', function($id) {
		$grade = GradeQuery::create()->findPK($id);

		if (!$grade) return;

		echo $grade->toJson();
	});

	$app->put('/api/grade/:id', function($id) use ($app) {
		$grade = GradeQuery::create()->findPK($id);

		if (!$grade) return;

		foreach ($app->request->put() as $key => $value) {
			$grade->setByName($key, $value);
		}

		$grade->save();

		echo $grade->toJson();
	});

	$app->post('/api/grade', function() use ($app) {
		$grade = new Grade();

		foreach ($app->request->post() as $key => $value) {
			$grade->setByName($key, $value);
		}

		$grade->save();

		echo $grade->toJson();
	});

	$app->delete('/api/grade/:id', function($id) {
		$grade = GradeQuery::create()->findPK($id);

		if (!$grade) return;

		$grade->delete();

		echo $grade->toJson();
	})
?>
