<?php

require ("connect.php");
if (empty ( $_GET ["q"] )) {
	$response = "";
} else {
	$search = mysql_real_escape_string ( $_GET ["q"] );
	$sql = "SELECT * FROM ToiduAined WHERE Kirjeldus=\"" . $search . "\"";
	$result = mysql_query ( $sql ) or die ( mysql_error () );
	if ($result) {
		if (mysql_num_rows ( $result ) == 0) {
			echo "<p>I'm sorry, but I couldn't find what You were looking for.</p>";
		} else {
			$row = array ();
			$row = mysql_fetch_array ( $result );
			$var1 = $row ['GmWt Desc1'];
			$var2 = $row ['GmWt Desc2'];
			if ($var1 == "0") {
				$var1 = "";
				$hideVar1 = true;
				$option1 = "";
			} else {
				$option1 = "<option>" . $var1 . "</option>";
			}
			if ($var2 == "0") {
				$var2 = "";
				$hideVar2 = true;
				$option2 = "";
			} else {
				$option2 = "<option>" . $var2 . "</option>";
			}
			echo $option1 . $option2 . '<option>gram(s)</option>';
		}
	} else {
		echo 'unsuccess';
	}
}
?>