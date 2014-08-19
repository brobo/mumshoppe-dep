<?php

	$encodeMum = function($mum) {

		$res = array(
			'Mum' => $mum,
			'Customer' => array(
				'Name' => $mum->getCustomer()->getName(),
				'Id' => $mum->getCustomer()->getId()
			),
			'Backing' => $mum->getBacking(),
			'Grade' => $mum->getBacking() ? $mum->getBacking()->getGrade() : null,
			'Size' => $mum->getBacking() ? $mum->getBacking()->getSize() : null,
			'Product' => $mum->getBacking() && $mum->getBacking()->getSize() ? $mum->getBacking()->getSize()->getProduct() : null,
			'Accent_bow' => $mum->getAccentBow(),
			'Status' => $mum->getStatus(),
			'Trinkets' => $mum->getMumTrinkets(),
			'Bears' => $mum->getBears()
		);

		foreach ($res as $key => $value) {
			if ($key == 'Customer') continue;
			if ($res[$key]) {
				$res[$key] = $res[$key]->toArray();
			}
		}

		for ($i = 0; $i < count($res['Trinkets']); $i++) {
			$res['Trinkets'][$i]['Trinket'] = 
				TrinketQuery::create()->findPK($res['Trinkets'][$i]['TrinketId'])->toArray();
		}

		return $res;
	};

	$app->get('/api/mum/:mumId', function($mumId) use ($app, $encodeMum) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		echo json_encode($encodeMum($mum));
	});
	
	$app->post('/api/mum', function() use ($app, $encodeMum) {
		$mum = new Mum();
		$mum->setCustomerId($app->request->post('CustomerId'));
		$mum->save();

		echo json_encode($encodeMum($mum));
	});

	$app->put('/api/mum/:mumId', function($mumId) use ($app, $encodeMum) {
		$mum = MumQuery::create()->findPK($mumId);

		if (!$mum) return;

		foreach ($app->request->put() as $key => $value) {
			$mum->setByName($key, $value);
		}

		$mum->save();
		echo json_encode($encodeMum($mum));
	});

	$app->post('/api/mum/:mumId/trinket/:trinketId', function($mumId, $trinketId) use ($app, $encodeMum) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		$trinket = TrinketQuery::create()->findPK($trinketId);
		if (!$trinket) return;
		$mumTrinket = MumTrinketQuery::create()->filterByMumId($mumId)->filterByTrinketId($trinketId)->findOne();
		if ($mumTrinket) {
			$mumTrinket->delete();
		}
		$mumTrinket = new MumTrinket();
		$mumTrinket->setMumId($mumId);
		$mumTrinket->setTrinketId($trinketId);
		$mumTrinket->setQuantity($app->request->post('Quantity'));
		$mumTrinket->save();

		echo json_encode(array('message' => 'Success'));
	});

	$app->delete('/api/mum/:mumId/trinket/:trinketId', function($mumId, $trinketId) use ($app, $encodeMum) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		$trinket = TrinketQuery::create()->findPK($trinketId);
		$mum->removeTrinket($trinket);

		$mum->save();
		echo json_encode($encodeMum($mum));
	});

	$app->put('/api/mum/:mumId/bear/:bearId', function($mumId, $bearId) use ($app, $encodeMum) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		$bear = BearQuery::create()->findPK($bearId);
		if (!$bear) return;
		if(!$mum->getBears(BearQuery::create()->filterById($bearId))) return;
		$mum->addBear($bear);
		$mum->save();

		echo json_encode(array('message' => 'Success'));
	});

	$app->delete('/api/mum/:mumId/bear/:bearId', function($mumId, $bearId) use ($app, $encodeMum) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		$bear = BearQuery::create()->findPK($bearId);
		$mum->removeBear($bear);

		$mum->save();
		echo json_encode($encodeMum($mum));
	});

	$app->get('/api/mum', function() use ($app, $encodeMum) {
		$mums = MumQuery::create()->find()->getData();

		echo json_encode(array_map($encodeMum, $mums));
	});

	$app->delete('/api/mum/:mumId', function($mumId) use ($encodeMum) {
		$mum = MumQuery::create()->findPk($mumId);
		if (!$mum) return;
		$mum->delete();

		echo json_encode($encodeMum($mum));
	});

?>