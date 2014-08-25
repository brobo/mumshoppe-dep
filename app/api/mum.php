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
			return $mum->getFull();
		};

		echo json_encode(array_map($encodeMum, $mums));
	});

	$app->get('/api/mum/:mumId', function($mumId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		echo json_encode($mum->getFull());
	});
	
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

	$app->post('/api/mum/:mumId/trinket', function($mumId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		foreach ($mum->getMumTrinkets() as $mumTrinket) {
			$mumTrinket->delete();
		}
		foreach ($app->request->post() as $trinketId => $quantity) {
			$mumTrinket = new MumTrinket();
			$mumTrinket->setMumId($mumId);
			$mumTrinket->setTrinketId($trinketId);
			$mumTrinket->setQuantity($quantity);
			$mumTrinket->save();
		}

		echo json_encode(array('message'=>'Successfully saved.'));
	});

	$app->post('/api/mum/:mumId/trinket/:trinketId', function($mumId, $trinketId) use ($app) {
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

	$app->delete('/api/mum/:mumId/trinket/:trinketId', function($mumId, $trinketId) use ($app) {
		$mum = MumQuery::create()->findPK($mumId);
		if (!$mum) return;
		$trinket = TrinketQuery::create()->findPK($trinketId);
		$mum->removeTrinket($trinket);

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

?>