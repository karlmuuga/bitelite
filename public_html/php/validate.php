<?php
// ÄKKI TA ON SISSE LOGITUD?
if (isset ( $_SESSION ['token'] )) {
	header ( "Location: /account" );
}
require_once ("connect.php");
$login_error_message = "";
$error = "";
// KUI REGISTREERIB:
if (isset ( $_POST ['registerSubmit'] )) {
	
	$registerError = array ();
	
	/*
	 * if(isset($_POST['captcha'])){
	 * $answer = $_POST['captcha'];
	 * try{
	 * $captcha->checkAnswer($answer);
	 * }catch(Exception $exc){
	 * $error = "<p class='cError'>".$exc->getMessage()."</p>";
	 * $registerError[] = "SHIT";
	 * }
	 * }else{
	 * $error = "<p class='cError'>You entered no captcha!</p>";
	 * $registerError[] = "SHIT";
	 * }
	 */
	
	if (empty ( $_POST ['restaurantName'] )) {
		$emptyDiner = true;
	} else {
		$restaurantName = substr ( mysql_real_escape_string ( $_POST ['restaurantName'] ), 0, 80 );
		$emptyDiner = false;
	}
	
	if (empty ( $_POST ['registerEmail'] )) {
		$emptyEmail = true;
	} else if (preg_match ( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST ['registerEmail'] )) {
		$email = strtolower ( mysql_real_escape_string ( $_POST ['registerEmail'] ) );
		$emptyEmail = false;
	} else {
		$register_error_message = "Tried to enter invalid e-mail address";
	}
	
	if (empty ( $registerError )) {
		$result = mysql_query ( " SELECT * FROM users WHERE (email='$email');" ) or die ( mysql_error () );
		if (mysql_num_rows ( $result ) == 0) {
			
			$key = md5 ( uniqid ( rand (), true ) );
			$unCrypt = substr ( $key, 0, 8 ); // PASSWORD
			                            
			// SET SECURE TOKEN
			$token = md5 ( uniqid ( rand (), true ) );
			$check = mysql_query ( "SELECT * FROM users WHERE secure='$token';" ) or die ( mysql_error () );
			while ( mysql_num_rows ( $check ) != 0 ) {
				$token = md5 ( uniqid ( rand (), true ) );
				$check = mysql_query ( "SELECT * FROM users WHERE secure='$token';" ) or die ( mysql_error () );
			}
			$passwd = hash ( "sha512", $unCrypt );
			$result2 = mysql_query ( " INSERT INTO users (user_id,dinerName,email,password,secure) 
			VALUES ('','$restaurantName','$email','$passwd','$token');" ) or die ( mysql_error () );
			if (! result2) {
				die ( 'Could not insert into database: ' . mysql_error () );
			} else {
				$message = "Hi!\nWe are extremely glad You took the time to register on our site. To log in, please use the following password: \n\n" . $unCrypt . "\n\n\nWith kind regards,\nBiteLite team";
				$subject = "Registration on BiteLite";
				$headers = "From:donotreply@mail.bitelite.org";
				mail ( $email, $subject, $message, $headers );
				header ( 'Location: prompt.php?x=1' );
			}
		} else {
			header ( 'Location: prompt.php?x=2' );
		}
	}
}

// KUI LOGIB SISSE:
if (isset ( $_POST ['loginSubmit'] )) {
	$loginError = array ();
	
	if (empty ( $_POST ['loginEmail'] )) {
		$loginError [] = 'Please enter Your email. ';
		$emptyEmail2 = true;
	} else {
		$email2 = strtolower ( mysql_real_escape_string ( $_POST ['loginEmail'] ) );
		$emptyEmail2 = false;
	}
	
	if (empty ( $_POST ['loginPassword'] )) {
		$loginError [] = 'Please enter a password.';
		$emptyPass2 = true;
	} else {
		$unsecure2 = $_POST ['loginPassword'];
		$password2 = hash ( "sha512", $unsecure2 );
		$cryptedPass2 = mysql_real_escape_string ( $password2 );
		$emptyPass2 = false;
	}
	
	if (empty ( $loginError )) {
		$result3 = mysql_query ( " SELECT * FROM users WHERE email='$email2' AND password='$cryptedPass2' " ) or die ( mysql_error () );
		if (mysql_num_rows ( $result3 ) == 1) {
			while ( $row = mysql_fetch_array ( $result3 ) ) {
				$_SESSION ['token'] = $row ['secure'];
				header ( 'Location:http://www.bitelite.org/account' );
			}
		} else {
			$login_error_message = "";
			$login_error_message = "<span class='loginError'>I'm incredibly sorry, 
			but the email-password combination You entered seems to be incorrect.</span>";
		}
	} else {
		// LOGIN ERROR
		if ($emptyPass2 && $emptyEmail2) {
			$login_error_message = "";
			$login_error_message = "<span class='loginError'>I'm incredibly sorry, 
			but You must enter both Your e-mail and password in order to log into our site.</span>";
		} else if ($emptyPass2) {
			
			$login_error_message = "";
			$login_error_message = "<span class='loginError'>I'm incredibly sorry, 
			but You must enter Your password in order to log into our site.</span>";
		} else if ($emptyEmail2) {
			
			$login_error_message = "";
			$login_error_message = "<span class='loginError'>I'm incredibly sorry, 
			but You must enter Your e-mail in order to log into our site.</span>";
		}
	}
}

// KUI VAJUTAB PAROOLI NUPPU
if (isset ( $_POST ['recover'] )) {
	require ("connect.php");
	$email = $_POST ['currentEmail'];
	$result = mysql_query ( "SELECT * FROM users WHERE email='$email';" );
	
	if (! $result or mysql_num_rows ( $result ) == 0) {
		header ( "Location: http://www.bitelite.org/prompt.php?x=" . urlencode ( "No user with email " . $email ) );
		die ();
	} else {
		
		$arr = mysql_fetch_row ( $result );
		$name = $arr ['dinerName'];
		
		$key = md5 ( uniqid ( rand (), true ) );
		$unCrypt2 = substr ( $key, 0, 8 ); // PASSWORD
		$passwd = hash ( "sha512", $unCrypt2 );
		
		$subject = "Password for BiteLite";
		$message = "Hi!\nNew password is " . $unCrypt2 . ".\n\n\nWith kind regards,\nBiteLite team";
		$from = "donotreply@m.bitelite.org";
		$headers = "From:" . $from;
		mail ( $email, $subject, $message, $headers );
		$changeP = mysql_query ( "UPDATE users SET password='$passwd' WHERE email='$email';" ) or die ( mysql_error () );
		
		if ($changeP) {
			header ( "Location: http://www.bitelite.org/prompt.php?x=" . urlencode ( "Your password was updated successfully! Check your mail!" ) );
			die ();
		}
	}
}

?>