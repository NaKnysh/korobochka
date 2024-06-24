<?php
	include 'db_connect.php';

	// Отримання ID з URL
	$box_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

	if ($box_id > 0) {
		$query = "SELECT * FROM boxtype WHERE id = $box_id";
		$result = mysqli_query($connection, $query);

		if ($result && mysqli_num_rows($result) > 0) {
			$box = mysqli_fetch_assoc($result);
			echo '<h1>' . $box['name'] . '</h1>';
			echo '<p>' . $box['description'] . '</p>';
			echo '<p>Ціна: ' . $box['price'] . ' $</p>';
			// Припустимо, що possibleItemsId - це кома-розділений рядок ID елементів
			$items_ids = explode(',', $box['possibleItemsId']);
			$items_query = "SELECT * FROM item WHERE id IN (" . implode(',', $items_ids) . ")";
			$items_result = mysqli_query($connection, $items_query);

			if ($items_result && mysqli_num_rows($items_result) > 0) {
				echo '<h2>Предмети в коробці:</h2>';
				echo '<ul>';
				while ($item = mysqli_fetch_assoc($items_result)) {
					echo '<li>' . $item['name'] . ' - ' . $item['description'] . '</li>';
				}
				echo '</ul>';
			} else {
				echo '<p>Немає доступних предметів у цій коробці.</p>';
			}
		} else {
			echo '<p>Коробку не знайдено.</p>';
		}
	} else {
		echo '<p>Некоректний ID коробки.</p>';
	}
?>

<a href="index.php">
    <button style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Назад</button>
</a>