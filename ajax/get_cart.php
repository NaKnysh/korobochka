<?php	
	session_start();
	
	require_once('../items.php');
	if ( !isset($_SESSION['items_in_cart']) ) {
		$_SESSION['items_in_cart'] = array();
	}
	
	$result = '';
	if ( !empty($_SESSION['items_in_cart']) ) {
		foreach($_SESSION['items_in_cart'] as $item_in_cart) {
			if ( !empty($items[$item_in_cart]) ) {
				$item = $items[$item_in_cart];
				
				$result .= $item['title']." - ".$item['price']."<a href='#' class='del_bnt' onclick='del_from_cart(".$item['id'].")'> <i class='fa fa-times' aria-hidden='true'></i></a> <br><hr>";
			}
		}
		
		$result .= '<br><a href="checkout.php">checkout</a>';
	} else {
		$result = "<b>cart is empty</b>";
	}
	
	
	print($result);
?>