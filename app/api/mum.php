<?php

	$app->get('/api/mum', function() use ($app) {
		$mums = MumQuery::create();

		foreach ($app->request->get() as $key => $value) {
			switch ($key) {
			case 'CustomerName':
				$mums = $mums->joinWith('Mum.Customer')->where('Customer.Name LIKE ?', "%$value%");
				break;
			case 'Year':
				if ($value)
					$mums = $mums->filterByOrderDate(array('min' => $value . '-01-01 00:00:00', 'max' => $value . '-12-31 23:59:59'));
				break;
			case 'Ordered':
				if ($value && $value != "false") {
					$mums = $mums->filterByStatusId(array('min' => 2));
				}
				break;
			case 'Unordered':
				if ($value && $value != "false") {
					$mums = $mums->filterByStatusId(1);
				}
				break;
			}
		}

		$mums = $mums->find()->getData();

		$encodeMum = function($mum) {
			return $mum->getMini();
		};

		echo json_encode(array_map($encodeMum, $mums));
	});

	$app->get('/api/mum/:mumId', function($mumId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		echo json_encode($mum->getFull());
	});
	
	// TODO: You can query whether or not to allow further orders by $app->persisted->get("AllowOrders").
	// Accept/Deny based on that constant.
	$app->post('/api/mum', function() use ($app) {
		$mum = new Mum();
		$mum->setCustomerId($app->request->post('CustomerId'));
		$mum->save();

		echo json_encode($mum->getFull());
	});

	$app->put('/api/mum/:mumId', function($mumId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);

		if (!$mum) return;

		foreach ($app->request->put() as $key => $value) {
			$mum->setByName($key, $value);
		}

		$mum->save();
		echo json_encode($mum->getFull());
	});

	$app->put('/api/mum/:mumId/status', function($mumId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);

		if (!$mum) return;

		$mum->setStatusId($app->request->put('StatusId'));

		$mum->save();
		echo json_encode($mum->getFull());
	});
	
	// Stop any further mums from being ordered.
	$app->post("/api/mum/stop-orders", auth_volunteer(VolunteerRights::ToggleOrders), function() use ($app) {
		$app->persisted->set("AllowOrders", false);
		$app->persisted->save();
	});
	
	// Allow further orders.
	$app->post("/api/mum/start-orders", auth_volunteer(VolunteerRights::ToggleOrders), function() use ($app) {
		$app->persisted->set("AllowOrders", true);
		$app->persisted->save();			
	});
	
	// Query if orders are allowed.
	$app->get("/api/mum/can-order", function() use ($app) {
		echo json_encode(["AllowOrders" => $app->persisted->get("AllowOrders")]);
	});

	$app->post('/api/mum/:mumId/accessory', function($mumId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		foreach ($mum->getMumAccessories() as $mumAccessory) {
			$mumAccessory->delete();
		}
		foreach ($app->request->post() as $accessoryId => $quantity) {
			$mumAccessory = new MumAccessory();
			$mumAccessory->setMumId($mumId);
			$mumAccessory->setAccessoryId($accessoryId);
			$mumAccessory->setQuantity($quantity);
			$mumAccessory->save();
		}

		echo json_encode(array('message'=>'Successfully saved.'));
	});

	$app->post('/api/mum/:mumId/accessory/:accessoryId', function($mumId, $accessoryId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		$accessory = AccessoryQuery::create()->findPK($accessoryId);
		if (!$accessory) return;
		$mumAccessory = MumAccessoryQuery::create()->filterByMumId($mumId)->filterByAccessoryId($accessoryId)->findOne();
		if ($mumAccessory) {
			$mumAccessory->delete();
		}
		$mumAccessory = new MumAccessory();
		$mumAccessory->setMumId($mumId);
		$mumAccessory->setAccessoryId($accessoryId);
		$mumAccessory->setQuantity($app->request->post('Quantity'));
		$mumAccessory->save();

		echo json_encode(array('message' => 'Success'));
	});

	$app->delete('/api/mum/:mumId/accessory/:accessoryId', function($mumId, $accessoryId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		$accessory = AccessoryQuery::create()->findPK($accessoryId);
		$mum->removeAccessory($accessory);

		$mum->save();
		echo json_encode($mum->getFull());
	});

	$app->put('/api/mum/:mumId/bear/:bearId', function($mumId, $bearId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		$bear = BearQuery::create()->findPK($bearId);
		if (!$bear) return;
		if(!$mum->getBears(BearQuery::create()->filterById($bearId))) return;
		$mum->addBear($bear);
		$mum->save();

		echo json_encode(array('message' => 'Success'));
	});

	$app->delete('/api/mum/:mumId/bear/:bearId', function($mumId, $bearId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		$bear = BearQuery::create()->findPK($bearId);
		$mum->removeBear($bear);

		$mum->save();
		echo json_encode($mum->getFull());
	});

	$app->delete('/api/mum/:mumId', function($mumId) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		$mum->delete();

		echo json_encode($mum->getFull());
	});
	
	$app->delete("/api/mum", function() {
		MumQuery::create()->delete(); // That is frighteningly simple
		
		echo json_encode(["message" => "Successfully truncated mums table."]);
	});

?>