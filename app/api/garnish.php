<?php

	$app->post('/api/garnish', function() use ($app, $mysqli) {
		$name = $app->request->post('name');
		$underclassman = $app->request->post('underclassman');
		$junior = $app->request->post('junior');
		$senior = $app->request->post('senior');
		$price = $app->request->post('price');

		$create_stmt = $mysqli->prepare("
			INSERT INTO garnish
			(name, underclassman, junior, senior, price)
			VALUES
			(?, ?, ?, ?, ?)");
		
		$create_stmt->bind_param("siiid", $name, $underclassman, $junior, $senior, $price);
		if ($create_stmt->execute()) {
			echo "Successfully created garnish.";
		} else {
			echo "Error while creating garnish: " . $create_stmt->error;
		}
	});

	$app->get('/api/garnish', function() use ($app, $mysqli) {
		
		$select_stmt = $mysqli->prepare("SELECT name, underclassman, junior, senior, price FROM garnish");

		if ($select_stmt->execute()) {
			$select_stmt->bind_result($name, $underclassman, $junior, $senior, $price);

			$output = array();

			while ($select_stmt->fetch()) {
				$output[] = array(
					"name" => $name, 
					"underclassman" => $underclassman, 
					"junior" => $junior, 
					"senior" => $senior, 
					"price" => $price);
			}

			echo json_encode($output);
		} else {
			echo "Error while getting garnishes: " . $select_stmt->error;
		}
	});

	$app->get('/api/garnish/:id')

?>