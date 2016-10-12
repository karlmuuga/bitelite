<?php

if (isset ( $_GET ['registerEmail'] )) {
	
	require ("connect.php");
	$email = mysql_real_escape_string ( $_GET ['registerEmail'] );
	$query = "SELECT * FROM users WHERE email='$email' LIMIT 1;";
	$results = mysql_query ( $query ) or die ( mysql_error () );
	if ($results && mysql_num_rows ( $results ) == 0) {
		echo "true"; // good to register
	} else {
		echo "false"; // already registered
	}
} else {
	echo "false"; // invalid post var
}

?>