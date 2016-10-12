<?php
session_start ();
if (isset ( $_GET ['url'] ) && isset ( $_SESSION ['token'] )) {
	require ("connect.php");
	
	$token = $_SESSION ['token'];
	$checkUserID = mysql_query ( "SELECT * FROM `users` WHERE secure='$token' LIMIT 1" ) or die ( mysql_error () );
	if (mysql_num_rows ( $checkUserID ) == 0) {
		die ( "You are not an user!" );
	}
	$arr = mysql_fetch_array ( $checkUserID );
	$id = $arr ['user_id'];
	if ($arr ['isVerified'] == '1') {
		die ( "Your account has already been verified!" );
	}
	$err = "Sorry! Something went wrong during the update of your data...  ";
	
	$url = addslashes ( $_GET ['url'] );
	$result = mysql_query ( "SELECT * FROM users WHERE verUrl='$url' AND user_id<>'$id' LIMIT 1" ) or die ( mysql_error () );
	if (mysql_num_rows ( $result ) == 0) {
		$arr = mysql_fetch_array ( $result );
		
		if ($arr ['verKey'] == "") {
			$key = md5 ( uniqid ( rand (), true ) );
			$result = mysql_query ( "UPDATE users SET verKey='$key',verUrl='$url' WHERE user_id='$id'" ) or die ( mysql_error () );
			
			if ($result) {
				echo $key;
			}
		} else {
			echo $arr ['verKey'];
		}
	} else {
		die ( "This URL is already taken!" );
	}
} else {
	die ( "You are not an user!" );
}
?>