<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

	$encodeAccessory = function($accessory) {
		return array(
			"Id" => $accessory->getId(),
			"ItemId" => $accessory->getItemId(),
			"Name" => $accessory->getName(),
			"Underclassman" => $accessory->getUnderclassman(),
			"Junior" => $accessory->getJunior(),
			"Senior" => $accessory->getSenior(),
			"Price" => $accessory->getPrice(),
			"HasImage" => $accessory->getImageMime() !== "",
			"CategoryId" => $accessory->getCategoryId(),
			"AccessoryCategory" => $accessory->getAccessoryCategory()->toArray()
		);
	};

	$app->get('/api/accessory', auth_all(VolunteerRights::ConfigureItems), function() use ($encodeAccessory) {
		$accessories = AccessoryQuery::create()->joinWith('AccessoryCategory')->find()->getData();
		
		if (!$accessories) return;

		echo json_encode(array_map($encodeAccessory, $accessories));
	});

	$app->get('/api/accessory/:id', auth_all(VolunteerRights::ConfigureItems), function($id) use ($encodeAccessory) {
		$accessory = AccessoryQuery::create()->joinWith('Accessory.AccessoryCategory')->findPK($id);
		
		if (!$accessory) return;

		echo json_encode($encodeAccessory($accessory));
	});

	$app->put('/api/accessory/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($app, $encodeAccessory) {

		$accessory = AccessoryQuery::create()->findPK($id);

		if (!$accessory) return;

		foreach ($app->request->put() as $key => $value) {
			try {
				$accessory->setByName($key, $value);
			} catch (Exception $ex) {}
		}

		$accessory->save();

		echo json_encode($encodeAccessory($accessory));
	});

	$app->post('/api/accessory', auth_volunteer(VolunteerRights::ConfigureItems), function() use ($app, $encodeAccessory) {
		$accessory = new Accessory();

		foreach ($app->request->post() as $key => $value) {
			try {
				$accessory->setByName($key, $value);
			} catch (Exception $ex) {}
		}

		$accessory->save();

		echo json_encode($encodeAccessory($accessory));
	});

	$app->delete('/api/accessory/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($encodeAccessory) {
		$accessory = AccessoryQuery::create()->findPK($id);

		if (!$accessory) return;

		$accessory->delete();
		
		echo json_encode($encodeAccessory($accessory));
	});

	$app->post('/api/accessory/:id/image', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$accessory = AccessoryQuery::create()->findPK($id);
		if (!$accessory) return;

		$content = file_get_contents($_FILES['image']['tmp_name']);
		$accessory->setImage($content);
		$accessory->setImageMime($_FILES['image']['type']);

		$accessory->save();
		echo json_encode(array('message' => 'Success!'));
	});

	$app->get('/api/accessory/:id/image', function($id) use ($app) {
		$app->response->header('Content-Type', 'content-type: image/jpg');

		$accessory = AccessoryQuery::create()->findPK($id);
		if (!$accessory) return;

		$fp = $accessory->getImage();

		$res = $app->response();
		if ($fp !== null) {
			$content = stream_get_contents($fp, -1, 0);
			$res->header('Content-Type', 'content-type: ' . $accessory->getImageMime());
			echo $content;
		}
	});

?>
