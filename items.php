<?php

	include 'db_connect.php';

	// Отримання даних з таблиці boxtype
	$sql = "SELECT id, name, description, price FROM boxtype";
	$result = $connection->query($sql);

	$items = array();

	if ($result->num_rows > 0) {
		// Перебір результатів запиту
		while ($row = $result->fetch_assoc()) {
			$item = array();
			$item['id'] = $row['id'];
			$item['title'] = $row['name'];
			$item['text'] = $row['description'];
			$item['price'] = '$' . number_format($row['price'], 2);
			$item['picture'] = 'assets/images/box11.jpg'; // Захардкоджена картинка
			$items[$item['id']] = $item;
		}
	} else {
		echo "0 results";
	}
  
?>