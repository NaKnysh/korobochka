<?php
	session_start();

	if (isset($_GET['user_name']) && isset($_GET['userToken'])) {
		$_SESSION['user_name'] = $_GET['user_name'];
		$_SESSION['userToken'] = $_GET['userToken'];
		
		header("Location: http://korobochka.local/");
		exit();
	} else {
		header("Location: http://korobochka.local/login.php");
		exit();
	}
?>