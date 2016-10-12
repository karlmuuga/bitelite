<?php
session_start ();
// KONTROLLI ANDMEID
if (isset ( $_SESSION ['token'] ) && isset ( $_GET ['q'] )) {
	
	$token = $_SESSION ['token'];
	require ("connect.php");
	
	$dishName = addslashes ( $_GET ['q'] );
	
	// KONTROLLI KASUTAJAT
	
	$checkUserID = mysql_query ( "SELECT * FROM `users` WHERE secure='$token' LIMIT 1;" ) or die ( mysql_error () );
	if (mysql_num_rows ( $checkUserID ) == 0) {
		die ( "You are not an user!" );
	}
	$checkUserID = mysql_query ( "SELECT * FROM `users` WHERE secure='$token' LIMIT 1;" ) or die ( mysql_error () );
	$arr = mysql_fetch_array ( $checkUserID );
	$user_id = $arr ['user_id'];
	
	$result = mysql_query ( " SELECT * FROM foods WHERE `dish_name`=\"" . $dishName . "\" AND own='$user_id';" );
	if (! $result or mysql_num_rows ( $result ) == 0) {
		echo "<p>I'm sorry, but I couldn't find what You were looking for.</p>";
	} else {
		foreach ( mysql_fetch_array ( $result ) as $key2 => $values2 ) {
			$foodzArr [$key2] = urldecode ( $values2 );
		}
		$result = mysql_query ( "SELECT * FROM foods WHERE `dish_name`=\"" . $dishName . "\" AND own='$user_id';" ) or die ( mysql_error () );
		$row = mysql_fetch_row ( $result );
		$string = "";
		
		foreach ( $row as $key => $values ) {
			if ($values != "") {
				$string .= urldecode ( $values ) . "&&&";
			}
		}
		$cols = explode ( "&&&", $string );
		$ingridientsCount = (count ( $cols ) - 7) / 3;
		$foodName = $foodzArr ['dish_name'];
		$foodDesc = $foodzArr ['dish_desc'];
		$foodCat = $foodzArr ['dish_cat'];
		echo "<form id = 'foodInfo2'><h2>Rename</h2><input maxlength = '100' type = 'text' name = 'mealName2' id = 'mealName2' value = \"" . $foodName . "\">
		<h2>Description</h2><textarea maxlength = '700' name = 'mealDesc2' id = 'mealDesc2'>" . $foodDesc . "</textarea>
		<h2>Type of course</h2><select id = 'mealsCourse' name = 'mealCourse'><option selected>" . $foodCat . "</option>";
		if ($foodCat != "Appetizer") {
			echo "<option>Appetizer</option>";
		}
		if ($foodCat != "Breakfast") {
			echo "<option>Breakfast</option>";
		}
		if ($foodCat != "Dessert") {
			echo "<option>Dessert</option>";
		}
		if ($foodCat != "Drink") {
			echo "<option>Drink</option>";
		}
		if ($foodCat != "Lunch") {
			echo "<option>Lunch</option>";
		}
		if ($foodCat != "Main Course") {
			echo "<option>Main course</option>";
		}
		if ($foodCat != "Salad") {
			echo "<option>Salad</option>";
		}
		if ($foodCat != "Soup") {
			echo "<option>Soup</option>";
		}
		echo "</select>
		<h2>Ingredients</h2><div id = 'ingredients2'><input type = 'text' id = 'addItemSearch2' placeholder= 'Type here a name of ingredient, what can be found in Your meal'>
		<div id = 'foodResults2'></div><div id = 'addedFoods2'>";
		
		$i = 0;
		while ( $ingridientsCount > $i ) {
			$jrk = $i + 1;
			
			// VALI VAJALIK INFO
			$toiduaine = $foodzArr ['ingridient_num_' . $i];
			$kogus = $foodzArr ['weight_num_' . $i];
			$selectedUnit = $foodzArr ['unit_num_' . $i];
			echo '<div class = "newElement2">
			<input readonly class = "foodElement2" value = "' . $toiduaine . '" name = "foodItem' . $jrk . '">
			<input type = "text" placeholder = "Quantity" value = "' . $kogus . '" class = "mealWeight2" name = "weight' . $jrk . '">
			<select class = "mealUnit2" name = "unit' . $jrk . '"><option selected>' . $selectedUnit . '</option>';
			$sql = "SELECT * FROM ToiduAined WHERE Kirjeldus=\"" . addslashes ( $toiduaine ) . "\"";
			$result = mysql_query ( $sql ) or die ( mysql_error () );
			
			if ($result) {
				
				if (mysql_num_rows ( $result ) == 0) {
					echo "<p>I'm sorry, but I couldn't find what You were looking for.</p>";
				} else {
					$row = mysql_fetch_array ( $result );
					$var1 = $row ['GmWt Desc1'];
					$var2 = $row ['GmWt Desc2'];
					if ($var1 == "0" or $var1 == $selectedUnit) {
						$var1 = "";
						$option1 = "";
					} else {
						$option1 = "<option>" . $var1 . "</option>";
					}
					
					if ($var2 == "0" or $var2 == $selectedUnit) {
						$var2 = "";
						$option2 = "";
					} else {
						$option2 = "<option>" . $var2 . "</option>";
					}
					
					if ($selectedUnit != "gram(s)") {
						echo $option1 . $option2 . '<option>gram(s)</option>';
					} else {
						echo $option1 . $option2;
					}
				}
			} else {
				echo 'unsuccess';
			}
			echo "</select><input class = 'removeItem2' value = 'Remove' type = 'button'></div>";
			$i ++;
		}
		echo "</div></div></form>";
	}
}
?>