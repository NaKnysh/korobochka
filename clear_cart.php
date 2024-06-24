<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    unset($_SESSION['items_in_cart']); // Очищення кошика
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>