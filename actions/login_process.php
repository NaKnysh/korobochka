<?php
	session_start();

	$data = json_decode(file_get_contents('php://input'), true);

	if (isset($data['userName']) && isset($data['userToken'])) {
		$_SESSION['user_name'] = $data['userName'];
		$_SESSION['user_token'] = $data['userToken'];
		echo json_encode(['success' => true]);
	} else {
		echo json_encode(['success' => false]);
	}
?>