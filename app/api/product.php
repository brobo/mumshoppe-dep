<?php
	
	$app->get('/api/product', function() use ($app) {
		$products = ProductQuery::create()->find();

		if (!$products) return;

		echo $products->toJson();
	});

	$app->get('/api/product/:id', function($id) {
		$product = ProductQuery::create()->findPK($id);

		if (!$product) return;

		echo $product->toJson();
	});

	$app->put('/api/product/:id', function($id) use ($app) {
		$product = ProductQuery::create()->findPK($id);

		if (!$product) return;

		foreach ($app->request->put() as $key => $value) {
			$product->setByName($key, $value);
		}

		$product->save();

		echo $product->toJson();
	});

	$app->post('/api/product', function() use ($app) {
		$product = new Product();

		foreach ($app->request->post() as $key => $value) {
			$product->setByName($key, $value);
		}

		$product->save();

		echo $product->toJson();
	});

	$app->delete('/api/product/:id', function($id) {
		$product = ProductQuery::create()->findPK($id);

		if (!$product) return;

		$product->delete();

		echo $product->toJson();
	})
?>
