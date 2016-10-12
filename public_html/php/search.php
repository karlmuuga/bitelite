<?php

require ("connect.php");
if (empty ( $_GET ['diner'] )) {
	$response = "";
} else {
	if ($_GET ['diner'] == "%") {
		die ();
	}
	$search = strtolower ( mysql_real_escape_string ( $_GET ['diner'] ) );
	$query = explode ( " ", $search );
	$x = 1;
	$y = 1;
	
	// K�IGE T�HTSAM SKRIPT - PREMIUM OTSING
	$script = "SELECT * FROM users WHERE dinerName LIKE '%" . implode ( "%' AND isPremium='1' OR dinerName LIKE '%", $query ) . "%' AND isPremium='1' OR description LIKE '%" . implode ( "%' AND isPremium='1' OR description LIKE '%", $query ) . "%' AND isPremium='1' OR location LIKE '%" . implode ( "%' AND isPremium='1' OR location LIKE '%", $query ) . "%' AND isPremium='1' OR phone LIKE '%" . implode ( "%' AND isPremium='1' OR phone LIKE '%", $query ) . "%' ORDER BY isVerified DESC;";
	$sql = mysql_query ( $script ) or die ( mysql_error () );
	$y = mysql_num_rows ( $sql );
	
	while ( $arr = mysql_fetch_array ( $sql ) ) {
		$id = $arr ['user_id'];
		if ($arr ['isPremium'] == '1') {
			if (time () > strtotime ( $arr ['expireDate'] )) {
				$result4 = mysql_query ( "UPDATE users SET expireDate='',isPremium='0';" ) or die ( mysql_error () );
			}
		}
	}
	
	/*
	 * OTSI TOITU:
	 *
	 * $food = mysql_query("SELECT * FROM foods WHERE dish_name LIKE '%".
	 * implode("%' OR dish_name LIKE '%",$query)
	 * ."%' ORDER BY dish_name;") or die(mysql_error());
	 *
	 */
	
	$sql4 = mysql_query ( $script ) or die ( mysql_error () );
	$y = mysql_num_rows ( $sql4 );
	
	// KUI ESIMESE P�RINGU VASTUS POLE NULL
	if (mysql_num_rows ( $sql4 ) != 0) {
		echo "<h2>Premium diners</h2><ul>";
		while ( $resultArr = mysql_fetch_array ( $sql4 ) ) {
			echo "<li><a class = 'resultName' href = 'http://www.bitelite.org/diner/?id=" . $resultArr ['user_id'] . "'>" . $resultArr ['dinerName'] . "</a>";
			if ($resultArr ['isVerified'] == "1") {
				echo "<div class='verifiedRing' title='Verified user!'><p>V</p></div><span class='resultUrl'>(" . $resultArr ['verUrl'] . ")</span>";
			}
			echo "<h5 class = 'resultAddress' >" . $resultArr ['location'] . "</h5>
			<p class = 'resultDesc' >" . substr ( $resultArr ['description'], 0, 150 );
			if (strlen ( $resultArr ['description'] ) > 150) {
				echo '...';
			}
			echo "</p></li>";
		}
		echo "</ul>";
	}
	
	$p2ring = "SELECT * FROM users WHERE dinerName LIKE '%" . implode ( "%' AND isPremium='0' OR dinerName LIKE '%", $query ) . "%' AND isPremium='0' ORDER BY isVerified DESC";
	$result = mysql_query ( $p2ring ) or die ( mysql_error () );
	$x = mysql_num_rows ( $result );
	
	// KUI TEISE P�RINGU VASTUS POLE NULL
	if (mysql_num_rows ( $result ) != 0) {
		echo "<h2>Regular diners</h2><ul>";
		while ( $resultArr = mysql_fetch_array ( $result ) ) {
			echo "<li>
			<a class = 'resultName' href = 'http://www.bitelite.org/diner/?id=" . $resultArr ['user_id'] . "'>" . $resultArr ['dinerName'] . "</a>";
			if ($resultArr ['isVerified'] == "1") {
				echo "<div class='verifiedRing' title='Verified user!'><p>V</p></div><span class='resultUrl'>(" . $resultArr ['verUrl'] . ")</span>";
			}
			echo "<h5 class = 'resultAddress' >" . $resultArr ['location'] . "</h5>
			<p class = 'resultDesc' >" . substr ( $resultArr ['description'], 0, 150 );
			if (strlen ( $resultArr ['description'] ) > 150) {
				echo '...';
			}
			echo "</p></li>";
		}
		echo "</ul>";
	}
	
	// KUVA ERROR KUI VASTEID POLE
	if ($x == 0 && $y == 0) {
		die ( "<p id='both' class='searchError'>I'm sorry, but I couldn't find what You were looking for.</p>" );
	}
}

// TOIDU OTSING
if (empty ( $_GET ['food'] )) {
	$response2 = "";
} else {
	if ($_GET ['food'] == "%") {
		die ();
	}
	$search2 = strtolower ( mysql_real_escape_string ( $_GET ['food'] ) );
	$query2 = array ();
	$query2 = explode ( " ", $search2 );
	$p2ring2 = "SELECT * FROM ToiduAined WHERE Kirjeldus LIKE '%" . implode ( "%' AND Kirjeldus LIKE '%", $query2 ) . "%'";
	$result2 = mysql_query ( $p2ring2 ) or die ( mysql_error () );
	if (mysql_num_rows ( $result2 ) == 0) {
		$response2 = "<p class='searchError'>I'm sorry, but I couldn't find what You were looking for.</p>";
	} else {
		$response2 = "";
		$response2 = '<ul class="resultsList">';
		while ( $row2 = mysql_fetch_array ( $result2 ) ) {
			$response2 .= "
			<li>" . $row2 ['Kirjeldus'] . "</li>";
		}
		$response2 .= "</ul>";
	}
	echo $response2;
}
?>