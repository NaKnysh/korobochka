<?php
	session_start();

	$data = json_decode(file_get_contents('php://input'), true);

	if (isset($data['userName']) && isset($data['userToken'])) {
		$_SESSION['user_name'] = $data['userName'];
		$_SESSION['userToken'] = $data['userToken'];
		echo json_encode(['status' => 'success']);
	} else {
		echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
	}
?>