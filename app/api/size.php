<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/
	
	$app->get('/api/size', auth_all(VolunteerRights::ConfigureItems), function() use ($app) {
		$sizes = SizeQuery::create()->find();

		if (!$sizes) return;

		$encodeSize = function($size) {
			return $size->getFull();
		};

		echo json_encode(array_map($encodeSize, $sizes->getData()));
	});

	$app->get('/api/size/:id', auth_all(VolunteerRights::ConfigureItems), function($id) {
		$size = SizeQuery::create()->findPK($id);

		if (!$size) return;

		echo json_encode($size->getFull());
	});

	$app->put('/api/size/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($app) {
		$size = SizeQuery::create()->findPK($id);

		if (!$size) return;

		foreach ($app->request->put() as $key => $value) {
			$size->setByName($key, $value);
		}

		$size->save();

		echo json_encode($size->getFull());
	});

	$app->post('/api/size', auth_volunteer(VolunteerRights::ConfigureItems), function() use ($app) {
		$size = new Size();

		foreach ($app->request->post() as $key => $value) {
			$size->setByName($key, $value);
		}

		$size->save();

		echo json_encode($size->getFull());
	});

	$app->delete('/api/size/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$size = SizeQuery::create()->findPK($id);

		if (!$size) return;

		$size->delete();

		echo json_encode($size->getFull());
	});

	$app->post('/api/size/:id/image', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$size = SizeQuery::create()->findPK($id);
		if (!$size) return;

		$content = file_get_contents($_FILES['image']['tmp_name']);
		$size->setImage($content);
		$size->setImageMime($_FILES['image']['type']);

		$size->save();
		echo json_encode(array('message' => 'Success!'));
	});

	$app->get('/api/size/:id/image', function($id) use ($app) {
		$app->response->header('Content-Type', 'content-type: image/jpg');

		$size = SizeQuery::create()->findPK($id);
		if (!$size) return;

		$fp = $size->getImage();

		$res = $app->response();
		if ($fp !== null) {
			$content = stream_get_contents($fp, -1, 0);
			$res->header('Content-Type', 'content-type: ' . $size->getImageMime());
			echo $content;
		}
	});
?>
