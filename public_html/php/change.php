<?php
ini_set ( 'error_reporting', E_ALL | E_STRICT );
ini_set ( 'display_errors', 1 );
error_reporting ( ~ 0 );

session_start ();
require ("connect.php");
if (isset ( $_SESSION ['token'] )) {
	$token = $_SESSION ['token'];
	$cherckUserID = mysql_query ( "SELECT * FROM `users` WHERE secure='$token' LIMIT 1" ) or die ( mysql_error () );
	if (mysql_num_rows ( $cherckUserID ) == 0) {
		die ( "You are not an user!" );
	}
	$err = "Sorry! Something went wrong during the update of your data...  ";
	
	// UUS KONTAKTMEIL
	if (isset ( $_GET ['contact_email'] )) {
		$new_contact_email = $_GET ['contact_email'];
		$contactEmailQuery = mysql_query ( "UPDATE users SET contact_email='$new_contact_email' WHERE secure='$token'" ) or die ( mysql_error () );
		if (! $contactEmailQuery) {
			echo $err;
		} else {
			echo 'Your contact email has been changed to ' . $new_contact_email . ' successfully!';
		}
	}
	// UUS KIRJELDUS
	if (isset ( $_GET ['desc'] )) {
		$new_desc = mysql_real_escape_string ( $_GET ['desc'] );
		$descQuery = mysql_query ( "UPDATE users SET description='$new_desc' WHERE secure='$token';" ) or die ( mysql_error () );
		if (! $descQuery) {
			echo $err;
		} else {
			echo 'Your diners description has been changed to ' . $new_desc . ' successfully!';
		}
	}
	// UUS TELEFON
	if (isset ( $_GET ['phone'] )) {
		$new_phone = mysql_real_escape_string ( $_GET ['phone'] );
		$phoneQuery = mysql_query ( "UPDATE users SET phone='$new_phone' WHERE secure='$token'" ) or die ( mysql_error () );
		if (! $phoneQuery) {
			echo $err;
		} else {
			echo 'Your phone number has changed to ' . $new_phone . ' successfully!';
		}
	}
	// UUS ASUKOHT
	if (isset ( $_GET ['location'] )) {
		$new_location = mysql_real_escape_string ( $_GET ['location'] );
		$locationQuery = mysql_query ( "UPDATE users SET location='$new_location' WHERE secure='$token'" ) or die ( mysql_error () );
		if (! $locationQuery) {
			echo $err;
		} else {
			echo 'Your location information has changed to ' . $new_location . ' successfully!';
		}
	}
	
	if (isset ( $_POST ['passwd'] )) {
		$pass = $_POST ['passwd'];
		$new_pass = hash ( "sha512", $pass );
		$passQuery = mysql_query ( "UPDATE users SET password='$new_pass' WHERE secure='$token'" ) or die ( mysql_error () );
		if (! $passQuery) {
			echo $err;
		} else {
			echo 'Your password has changed successfully!';
		}
	}
	
	if (isset ( $_POST ['email'] )) {
		$email = $_POST ['email'];
		$key = uniqid ();
		$changeQuery = mysql_query ( "UPDATE users SET emailKey='$key' WHERE secure='$token'" ) or die ( mysql_error () );
		$message = "Hi!\nTo finish email change, click a link below: \nhttp://www.bitelite.org/php/change.php?key=" . $key . "&newmail=" . $email . "\n\n\nWith kind regards,\nBiteLite team";
		$subject = "Changes on BiteLite";
		$headers = "From:donotreply@mail.bitelite.org";
		mail ( $email, $subject, $message, $headers );
		if (! $changeQuery) {
			echo $err;
		}
	}
	
	if (isset ( $_GET ['key'] ) && isset ( $_GET ['newmail'] )) {
		$key = $_GET ['key'];
		$checkData = mysql_query ( "SELECT * FROM users WHERE emailKey='$key' AND secure='$token'" ) or die ( mysql_error () );
		if (! $checkData or mysql_num_rows ( $checkData ) == 0) {
			die ( "No such key!" );
		}
		$new_email = strtolower ( mysql_real_escape_string ( $_GET ['newmail'] ) );
		$emailQuery = mysql_query ( "UPDATE users SET email='$new_email',emailKey='' WHERE emailKey='$key' AND secure='$token'" ) or die ( mysql_error () );
		if (! $emailQuery) {
			echo $err;
		} else {
			header ( "Location: http://www.bitelite.org/prompt.php?x=" . urlencode ( 'Your contact-email has changed to "' . $new_email . '" successfully!' ) );
		}
	}
} else {
	die ( "You are not an user!" );
}
?>