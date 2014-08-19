<?php

	$encodeTrinket = function($trinket) {
		return array(
			"Id" => $trinket->getId(),
			"Name" => $trinket->getName(),
			"Underclassman" => $trinket->getUnderclassman(),
			"Junior" => $trinket->getJunior(),
			"Senior" => $trinket->getSenior(),
			"Price" => $trinket->getPrice(),
			"TrinketCategory" => $trinket->getTrinketCategory()->toArray()
		);
	};

	$app->get('/api/trinket', function() use ($encodeTrinket) {
		$trinkets = TrinketQuery::create()->joinWith('TrinketCategory')->find()->getData();
		
		if (!$trinkets) return;

		echo json_encode(array_map($encodeTrinket, $trinkets));
	});

	$app->get('/api/trinket/:id', function($id) use ($encodeTrinket) {
		$trinket = TrinketQuery::create()->joinWith('Trinket.TrinketCategory')->findPK($id);
		
		if (!$trinket) return;

		echo json_encode($encodeTrinket($trinket));
	});

	$app->put('/api/trinket/:id', function($id) use ($app, $encodeTrinket) {

		$trinket = TrinketQuery::create()->findPK($id);

		if (!$trinket) return;

		foreach ($app->request->put() as $key => $value) {
			try {
				$trinket->setByName($key, $value);
			} catch (Exception $ex) {}
		}

		$trinket->save();

		echo json_encode($encodeTrinket($trinket));
	});

	$app->post('/api/trinket', function() use ($app, $encodeTrinket) {
		$trinket = new Trinket();

		foreach ($app->request->post() as $key => $value) {
			try {
				$trinket->setByName($key, $value);
			} catch (Exception $ex) {}
		}

		$trinket->save();

		echo json_encode($encodeTrinket($trinket));
	});

	$app->delete('/api/trinket/:id', function($id) {
		$trinket = TrinketQuery::create()->findPK($id);

		if (!$trinket) return;

		$trinket->delete();
		
		echo json_encode($encodeTrinket($trinket));
	});

?>
