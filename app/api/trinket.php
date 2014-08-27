<?php

	$encodeTrinket = function($trinket) {
		return array(
			"Id" => $trinket->getId(),
			"Name" => $trinket->getName(),
			"Underclassman" => $trinket->getUnderclassman(),
			"Junior" => $trinket->getJunior(),
			"Senior" => $trinket->getSenior(),
			"Price" => $trinket->getPrice(),
			"HasImage" => $trinket->getImageMime() !== "",
			"CategoryId" => $trinket->getCategoryId(),
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

	$app->post('/api/trinket/:id/image', function($id) {
		$trinket = TrinketQuery::create()->findPK($id);
		if (!$trinket) return;

		$content = file_get_contents($_FILES['image']['tmp_name']);
		$trinket->setImage($content);
		$trinket->setImageMime($_FILES['image']['type']);

		$trinket->save();
		echo json_encode(array('message' => 'Success!'));
	});

	$app->get('/api/trinket/:id/image', function($id) use ($app) {
		$app->response->header('Content-Type', 'content-type: image/jpg');

		$trinket = TrinketQuery::create()->findPK($id);
		if (!$trinket) return;

		$fp = $trinket->getImage();

		$res = $app->response();
		if ($fp !== null) {
			$content = stream_get_contents($fp, -1, 0);
			$res->header('Content-Type', 'content-type: ' . $trinket->getImageMime());
			echo $content;
		}
	});

?>
