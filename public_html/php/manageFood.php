<?php

session_start ();
if (isset ( $_SESSION ['token'] )) {
	require ("connect.php");
	
	// VÕTA ANDMEBAASIST KASUTAJAANEMED
	$token = $_SESSION ['token'];
	$checkUserID = mysql_query ( "SELECT * FROM `users` WHERE secure='$token' LIMIT 1;" ) or die ( mysql_error () );
	if (mysql_num_rows ( $checkUserID ) == 0) {
		die ( "You are not an user!" );
	}
	$userNames = mysql_fetch_array ( $checkUserID );
	$userName = addslashes ( $userNames ['dinerName'] );
	$userId = $userNames ['user_id'];
	
	if (isset ( $_GET ['new'] )) {
		$elements = explode ( "¤", $_GET ['new'] );
		$ingCount = ((count ( $elements )) - 3) / 3;
		$newFood = array ();
		
		foreach ( $elements as $key => $values ) {
			$newFood [] = addslashes ( $values );
		}
		
		// KONTROLLI ARVU
		if ($userNames ['isPremium'] == 0) {
			
			$checkDishNum = mysql_query ( "SELECT * FROM foods WHERE own='$userId'" );
			
			if (mysql_num_rows ( $checkDishNum ) >= 10) {
				die ( "foodLimit" );
			}
		}
		
		// VÕTA PÕHIANDMED
		$Dishname = $newFood [0];
		$Dishdesc = $newFood [1];
		$DishCat = $newFood [2];
		
		// KONTROLLI, EGA POLE JUBA SELLISE NIMEGA TOITU
		$checkDish = mysql_query ( "SELECT * FROM foods WHERE own='$userId' AND dish_name=\"" . $Dishname . "\";" );
		if (mysql_num_rows ( $checkDish ) != 0) {
			die ( "Sorry, but you already have a dish with the exact same name!" );
		}
		
		// PANE KIRJA TOIDU ToiduAined
		// TÄHTIS!!
		$mituToiduAinetOn = (count ( $newFood ) - 3) / 3;
		// PANE PAIKA TOIDU PÕHIANDMED
		$setData = mysql_query ( "INSERT INTO foods (foodId,own,userName, dish_name, dish_desc, dish_cat) VALUES ('','$userId','$userName','$Dishname','$Dishdesc','$DishCat');" ) or die ( mysql_error () );
		$i = 0;
		while ( $i < $mituToiduAinetOn ) {
			// ARRAY ARVUTUS
			$time = 3 + ($i * 3);
			
			$ingColumn = "ingridient_num_" . $i;
			$ingValue = $newFood [$time];
			$time ++;
			$weightColumn = "weight_num_" . $i;
			$weightValue = $newFood [$time];
			$time ++;
			$unitColumn = "unit_num_" . $i;
			$unitValue = $newFood [$time];
			
			$lisaVeerud = "ALTER TABLE foods ADD `$ingColumn` VARCHAR( 255 ) NOT NULL , ADD `$weightColumn` VARCHAR( 255 ) NOT NULL , ADD `$unitColumn` VARCHAR( 255 ) NOT NULL;";
			$uuendaVeerud = "UPDATE foods SET `$ingColumn`='$ingValue', `$weightColumn`='$weightValue', `$unitColumn`='$unitValue' WHERE `dish_name`='$Dishname' AND own='$userId';";
			
			$p2ring = mysql_query ( $lisaVeerud );
			$p2ring2 = mysql_query ( $uuendaVeerud ) or die ( mysql_error () );
			$i ++;
		}
		
		// RESPONSE DATA:
		if ($p2ring2) {
			die ( "SUCCESS" );
		} else {
			die ( "ERROR" );
		}
	}
	
	if (isset ( $_GET ['change'] ) && isset ( $_GET ['oldname'] )) {
		$oldName = addslashes ( $_GET ['oldname'] );
		$elements = explode ( "¤", $_GET ['change'] );
		$ingCount = ((count ( $elements )) - 3) / 3;
		$newFood = array ();
		
		foreach ( $elements as $key => $values ) {
			$newFood [] = addslashes ( $values );
		}
		
		// VÕTA PÕHIANDMED
		$Dishname = $newFood [0];
		$Dishdesc = $newFood [1];
		$DishCat = $newFood [2];
		
		// KONTROLLI, KAS TOIT ON OLEMAS
		$query = mysql_query ( "SELECT * FROM foods WHERE own='$userId' AND dish_name='$oldName'" );
		if (! $query or mysql_num_rows ( $query ) == 0) {
			die ( "You have no meal called \"" . $oldName . "\"." );
		}
		
		// PANE KIRJA TOIDU ToiduAined
		// TÄHTIS!!
		$mituToiduAinetOn = (count ( $newFood ) - 3) / 3;
		$i = 0;
		while ( $i < $mituToiduAinetOn ) {
			
			$time = 3 + ($i * 3);
			
			$ingColumn = "ingridient_num_" . $i;
			$ingValue = $newFood [$time];
			$time ++;
			$weightColumn = "weight_num_" . $i;
			$weightValue = $newFood [$time];
			$time ++;
			$unitColumn = "unit_num_" . $i;
			$unitValue = $newFood [$time];
			
			$lisaVeerud = "ALTER TABLE foods ADD  `$ingColumn` VARCHAR( 255 ) NOT NULL , ADD  `$weightColumn` VARCHAR( 255 ) NOT NULL , ADD  `$unitColumn` VARCHAR( 255 ) NOT NULL;";
			$uuendaVeerud = "UPDATE foods SET `$ingColumn`='$ingValue', `$weightColumn`='$weightValue', `$unitColumn`='$unitValue' WHERE dish_name='$oldName' AND own='$userId';";
			$p2ring = mysql_query ( $lisaVeerud );
			$p2ring2 = mysql_query ( $uuendaVeerud ) or die ( mysql_error () );
			$i ++;
		}
		
		// PANE PAIKA TOIDU PÕHIANDMED
		$setData = mysql_query ( "UPDATE foods SET dish_name='$Dishname',dish_desc='$Dishdesc',dish_cat='$DishCat' WHERE dish_name='$oldName' AND own='$userId';" ) or die ( mysql_error () );
		
		if ($setData) {
			die ( "SUCCESS" );
		} else {
			die ( "ERROR" );
		}
	}
	
	if (isset ( $_GET ['del'] )) {
		
		$food = $_GET ['del'];
		$delete = mysql_query ( "DELETE FROM `$userId` WHERE dish_name='$food' AND own='$userId';" ) or die ( mysql_error () );
		if ($delete) {
			die ( "SUCCESS" );
		} else {
			die ( "ERROR" );
		}
	}
} else {
	die ( "You can't be an user" );
}
?>