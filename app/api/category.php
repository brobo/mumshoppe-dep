<?php
	$app->get('/api/category', function() {
		$categories = AccessoryCategoryQuery::create()->find();
		
		if (!$categories) return;
		
		echo json_encode($categories->toArray());
	});

	$app->post('/api/category', function() use ($app) {
		$category = new AccessoryCategory();
		$category->setName($app->request->post('Name'));
		$category->save();

		echo $category->toJson();
	});

	$app->delete('/api/category/:id', function($id) {
		$category = AccessoryQuery::create()->findPK($id);

		if (!$category) return;

		$category->delete();
		
		echo $category->toJson();
	});
?>