<?php
	session_start();
	include $_SERVER['DOCUMENT_ROOT'] . '/config.php';

	$data = json_decode(file_get_contents('php://input'), true);

	if (isset($data['userName']) && isset($data['userToken'])) {
		$userName = $data['userName'];
		$userToken = $data['userToken'];

		$_SESSION['user_name'] = $userName;
		$_SESSION['user_token'] = $userToken;

		// Перевірка наявності користувача в базі даних
		$stmt = $pdo->prepare("SELECT * FROM user WHERE name = :name");
		$stmt->execute(['name' => $userName]);
		$user = $stmt->fetch();

		if ($user) {
			// Якщо користувач існує, оновлюємо токен і роль
			$stmt = $pdo->prepare("UPDATE user SET token = :token, role = 'user' WHERE name = :name");
			$stmt->execute(['token' => $userToken, 'name' => $userName]);
		} else {
			// Якщо користувач не існує, створюємо нового
			$stmt = $pdo->prepare("INSERT INTO user (name, token, role) VALUES (:name, :token, 'user')");
			$stmt->execute(['name' => $userName, 'token' => $userToken]);
		}

		echo json_encode(['success' => true]);
	} else {
		echo json_encode(['success' => false]);
	}
?>