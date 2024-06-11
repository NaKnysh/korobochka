<?php	
	session_start();
	
	if ( !isset($_SESSION['items_in_cart']) ) {
		$_SESSION['items_in_cart'] = array();
	}
	$return_arr = array(
		'result'=>'error',
		'items_in_cart'=>sizeof($_SESSION['items_in_cart'])
	);
	
	if ( !empty($_POST['id']) ) {
		$_SESSION['items_in_cart'][$_POST['id']]=$_POST['id'];
		
		$return_arr = array(
			'result'=>'ok',
			'items_in_cart'=>sizeof($_SESSION['items_in_cart'])
		);
	}
	
	
	print(json_encode($return_arr));
?>