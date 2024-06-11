<?php
require_once 'config.php';

// Спроба підключення до бази даних MySQL
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Перевірка підключення
if($connection === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>