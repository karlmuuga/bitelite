<?php
if (isset ( $_POST ['amount'] ) && isset ( $_POST ['merchant_fields'] )) {
	$token = $_POST ['merchant_fields'];
	require ("connect.php");
	$UserID = mysql_query ( "SELECT * FROM `users` WHERE secure='$token' LIMIT 1;" ) or die ( mysql_error () );
	if (mysql_num_rows ( $UserID ) == 0) {
		die ( "You are not an user!" );
	}
	$userNames = mysql_fetch_array ( $UserID );
	$userName = $userNames ['dinerName'];
	
	$months = $_POST ['amount'] / 8;
	function is_decimal($val) {
		return is_numeric ( $val ) && floor ( $val ) != $val;
	}
	
	if (is_decimal ( $num ) != 1) {
		
		$ep = date ( 'Y-m-d', strtotime ( "+" . $months . " months", strtotime ( date ( 'Y-m-d' ) ) ) );
		$result = mysql_query ( "UPDATE users SET isPremium='1',expireDate='$ep' WHERE secure='$token';" ) or die ( mysql_error () );
		
		if ($result) {
			$string = urlencode ( "Your account has been promoted to premium successfully!" );
			header ( "Location: http://www.bitelite.org/prompt.php?x=$string" );
		}
	} else {
		die ( 'The months is not a whole number!' );
	}
}
?>