<?php

session_start ();
if (isset ( $_SESSION ['token'] )) {
	
	require ("connect.php");
	$token = $_SESSION ['token'];
	
	$cherckUserID = mysql_query ( "SELECT * FROM `users` WHERE secure='$token' LIMIT 1" ) or die ( mysql_error () );
	$arr = mysql_fetch_array ( $cherckUserID );
	if (mysql_num_rows ( $cherckUserID ) == 0) {
		die ( "You are not an user!" );
	}
	$site = $arr ['verUrl'] . "/" . $arr ['verKey'] . ".txt";
	if (file_get_contents ( $site ) == $arr ['verKey']) {
		$result = mysql_query ( "UPDATE users SET verKey='',verUrl='',isVerified=1 WHERE secure='$token'" ) or die ( mysql_error () );
		
		if ($result) {
			die ( "Your account has been verifyed successfully!" );
		}
	} else {
		die ( "The given file does not contain the key you got, the file URL must be $site and it must contain ONLY $key! 
		Right now we see there is only $string, review the file and try again!" );
	}
}
?>