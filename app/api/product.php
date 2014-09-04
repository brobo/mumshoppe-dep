<?php
	
	$app->get('/api/product', auth_all(VolunteerRights::ConfigureItems), function() use ($app) {
		$products = ProductQuery::create()->find();

		if (!$products) return;

		echo json_encode($products->toArray());
	});

	$app->get('/api/product/:id', auth_all(VolunteerRights::ConfigureItems), function($id) {
		$product = ProductQuery::create()->findPK($id);

		if (!$product) return;

		echo $product->toJson();
	});

	$app->put('/api/product/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) use ($app) {
		$product = ProductQuery::create()->findPK($id);

		if (!$product) return;

		foreach ($app->request->put() as $key => $value) {
			$product->setByName($key, $value);
		}

		$product->save();

		echo $product->toJson();
	});

	$app->post('/api/product', auth_volunteer(VolunteerRights::ConfigureItems), function() use ($app) {
		$product = new Product();

		foreach ($app->request->post() as $key => $value) {
			$product->setByName($key, $value);
		}

		$product->save();

		echo $product->toJson();
	});

	$app->delete('/api/product/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$product = ProductQuery::create()->findPK($id);

		if (!$product) return;

		$product->delete();

		echo $product->toJson();
	})
?>
