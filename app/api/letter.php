<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

	$app->get('/api/letter', auth_all(VolunteerRights::ConfigureItems), function() {
		$letters = LetterQuery::create()->find();
		
		if (!$letters) return;
		
		echo json_encode($letters->toArray());
	});

	$app->get('/api/letter/:id', auth_all(VolunteerRights::ConfigureItems), function($id) {
		$letter = LetterQuery::create()->findPK($id);
		
		if (!$letter) return;

		echo $letter->toJson();
	});

	$app->put('/api/letter/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($app) {

		$letter = LetterQuery::create()->findPK($id);

		if (!$letter) return;

		foreach ($app->request->put() as $key => $value) {
			$letter->setByName($key, $value);
		}

		$letter->save();

		echo $letter->toJson();
	});

	$app->post('/api/letter', auth_volunteer(VolunteerRights::ConfigureItems), function() use ($app) {
		$letter = new Letter();

		foreach ($app->request->post() as $key => $value) {
			$letter->setByName($key, $value);
		}

		$letter->save();

		echo $letter->toJson();
	});

	$app->delete('/api/letter/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$letter = LetterQuery::create()->findPK($id);

		if (!$letter) return;

		$letter->delete();
		
		echo $letter->toJson();
	})

?>
