<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/
	 
	$app->get('/api/category', auth_all(VolunteerRights::ConfigureItems), function() {
		$categories = AccessoryCategoryQuery::create()->find();
		
		if (!$categories) return;
		
		echo json_encode($categories->toArray());
	});

	$app->post('/api/category', auth_volunteer(VolunteerRights::ConfigureItems), function() use ($app) {
		$category = new AccessoryCategory();
		$category->setName($app->request->post('Name'));
		$category->save();

		echo $category->toJson();
	});

	$app->delete('/api/category/:id', auth_volunteer(VolunteerRights::ConfigureItems), function($id) {
		$category = AccessoryQuery::create()->findPK($id);

		if (!$category) return;

		$category->delete();
		
		echo $category->toJson();
	});
?>