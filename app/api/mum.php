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
			'Trinkets' => $mum->getTrinkets()	
		);

		foreach ($res as $key => $value) {
			if ($key == 'Customer') continue;
			if ($res[$key]) {
				$res[$key] = $res[$key]->toArray();
			}
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