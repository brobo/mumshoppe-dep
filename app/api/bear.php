<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

	$app->get('/api/bear', auth_all(VolunteerRights::ConfigureItems), function() {
		$bears = BearQuery::create()->find();
		
		if (!$bears) return;

		$encodeBear = function($bear) {
			return $bear->getFull();
		};

		echo json_encode(array_map($encodeBear, $bears->getData()));
	});

	$app->get('/api/bear/:id', auth_all(VolunteerRights::ConfigureItems), function($id) {
		$bear = BearQuery::create()->findPK($id);
		
		if (!$bear) return;

		echo json_encode($bear->getFull());
	});

	$app->put('/api/bear/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($app) {

		$bear = BearQuery::create()->findPK($id);

		if (!$bear) return;

		foreach ($app->request->put() as $key => $value) {
			$bear->setByName($key, $value);
		}

		$bear->save();

		echo json_encode($bear->getFull());
	});

	$app->post('/api/bear', auth_volunteer(VolunteerRights::ConfigureItems), function() use ($app) {
		$bear = new Bear();

		foreach ($app->request->post() as $key => $value) {
			$bear->setByName($key, $value);
		}

		$bear->save();

		echo json_encode($bear->getFull());
	});

	$app->delete('/api/bear/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$bear = BearQuery::create()->findPK($id);

		if (!$bear) return;

		$bear->delete();
		
		echo json_encode($bear->getFull());
	});

	$app->post('/api/bear/:id/image', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$bear = BearQuery::create()->findPK($id);
		if (!$bear) return;

		$content = file_get_contents($_FILES['image']['tmp_name']);
		$bear->setImage($content);
		$bear->setImageMime($_FILES['image']['type']);

		$bear->save();
		echo json_encode(array('message' => 'Success!'));
	});

	$app->get('/api/bear/:id/image', function($id) use ($app) {
		$app->response->header('Content-Type', 'content-type: image/jpg');

		$bear = BearQuery::create()->findPK($id);
		if (!$bear) return;

		$fp = $bear->getImage();

		$res = $app->response();
		if ($fp !== null) {
			$content = stream_get_contents($fp, -1, 0);
			$res->header('Content-Type', 'content-type: ' . $bear->getImageMime());
			echo $content;
		}
	});

?>
