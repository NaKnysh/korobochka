<?php
session_start();
require_once('config.php'); // Підключення до бази даних

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['userName'])) {
    $userName = $data['userName'];

    // Отримання user_id з бази даних
    $stmt = $pdo->prepare("SELECT id FROM user WHERE name = ?");
    $stmt->execute([$userName]);
    $user = $stmt->fetch();

    if ($user) {
        $user_id = $user['id'];

        // Генерація коробок і створення замовлень для кожного товару в кошику
        foreach ($_SESSION['items_in_cart'] as $boxtype_id) {
            $box_id = generateBox($pdo, $boxtype_id);
            createOrder($pdo, $user_id, $box_id);
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}

function generateBox($conn, $boxtype_id) {
    // Отримання ціни та можливих елементів для даного boxtype_id
    $stmt = $conn->prepare("SELECT price, possibleItemsId FROM boxtype WHERE id = ?");
    $stmt->execute([$boxtype_id]);
    $stmt->bindColumn('price', $price);
    $stmt->bindColumn('possibleItemsId', $possible_items);
    $stmt->fetch();

    // Ініціалізація змінних
    $total_value = 0;
    $itemList = array();
    $possible_items_array = explode(",", $possible_items);

    // Отримання можливих елементів з таблиці item
    $placeholders = implode(',', array_fill(0, count($possible_items_array), '?'));
    $stmt = $conn->prepare("SELECT id, value, quantityInStock FROM item WHERE id IN ($placeholders) AND quantityInStock > 0 ORDER BY RAND()");
    $stmt->execute($possible_items_array);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Вибір елементів до тих пір, поки їхня загальна вартість не перевищить price
    foreach ($result as $row) {
        if ($total_value + $row['value'] <= $price) {
            $total_value += $row['value'];
            $itemList[] = $row['id'];

            // Зменшення кількості товару в наявності
            $update_stmt = $conn->prepare("UPDATE item SET quantityInStock = quantityInStock - 1 WHERE id = ?");
            $update_stmt->execute([$row['id']]);
        }
    }

    // Створення нового запису в таблиці box
    $itemListId = implode(",", $itemList);
    $stmt = $conn->prepare("INSERT INTO box (typeId, itemListId) VALUES (?, ?)");
    $stmt->execute([$boxtype_id, $itemListId]);
    $box_id = $conn->lastInsertId();

    return $box_id;
}

function createOrder($conn, $user_id, $box_id) {
    $date = date("Y-m-d");
    $status = "Processing";

    $stmt = $conn->prepare("INSERT INTO `boxorder` (userid, boxid, dateRecieved, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $box_id, $date, $status]);
}
?>
