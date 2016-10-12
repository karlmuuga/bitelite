<?php
if (isset ( $_GET ['name'] ) && isset ( $_GET ['id'] )) {
	require ("connect.php");
	$id = $_GET ['id'];
	$dish = mysql_real_escape_string ( $_GET ['name'] );
	$dishResult = mysql_query ( "SELECT * FROM foods WHERE `dish_name`=\"" . $dish . "\" AND own='$id';" );
	if (! $dishResult or mysql_num_rows ( $dishResult ) == 0) {
		echo "SQL error! - SELECT * FROM foods WHERE `dish_name`=\"" . $dish . "\" AND own='$id'";
		// header("http://www.bitelite.org/diner/?id=".$id);
	} else {
		
		$aineNimed = array ();
		$aineKaalud = array ();
		$aineÜhikud = array ();
		$aineÜhikuKaalud = array ();
		$aineKogus = array ();
		
		$water = array ();
		$energy = array ();
		$protein = array ();
		$carbohydrt = array ();
		$sugar = array ();
		$sodium = array ();
		$fa_sat = array ();
		$fa_mono = array ();
		$fa_poly = array ();
		$cholestr = array ();
		
		$toit = mysql_fetch_array ( $dishResult );
		$dishResult = mysql_query ( "SELECT * FROM foods WHERE `dish_name`=\"" . $dish . "\" AND own='$id' LIMIT 1;" );
		$read = mysql_fetch_row ( $dishResult );
		$nimi = mysql_real_escape_string ( $toit ['dish_name'] );
		$kirjeldus = mysql_real_escape_string ( $toit ['dish_desc'] );
		$cat = mysql_real_escape_string ( $toit ['dish_cat'] );
		$string = array ();
		$prestring = "";
		
		foreach ( $read as $key => $values ) {
			if ($values != "") {
				$prestring .= $values . "&&&";
			}
		}
		$string = explode ( "&&&", $prestring );
		$osadeArv = (count ( $string ) - 7) / 3;
		
		// TEE AINE NIMEDE ARRAY
		$i = 0;
		while ( $osadeArv > $i ) {
			$veerg = 6 + ($i * 3);
			$aineNimed [$i] = mysql_real_escape_string ( $read [$veerg] );
			$i ++;
		}
		
		// TEE AINE KOGUSTE ARRAY
		$i = 0;
		while ( $osadeArv > $i ) {
			$veerg = 7 + ($i * 3);
			$aineKaalud [$i] = $read [$veerg];
			$i ++;
		}
		
		// TEE AINE ÜHIKUTE ARRAY
		$i = 0;
		while ( $osadeArv > $i ) {
			$veerg = 8 + ($i * 3);
			$aineÜhikud [$i] = $read [$veerg];
			$i ++;
		}
		
		foreach ( $aineÜhikud as $key => $values ) {
			
			if ($values != "gram(s)") {
				
				$sql = "SELECT * FROM `ToiduAined` WHERE Kirjeldus=\"" . $aineNimed [$key] . "\";";
				// echo "[DEBUG:]".$sql."</br>";
				$result = mysql_query ( $sql ) or die ( mysql_error () );
				
				$nuts = mysql_fetch_row ( $result );
				
				if ($nuts [13] == $values) {
					$aineÜhikuKaalud [$key] = intval ( ($nuts [12]) ) / 100;
				}
				
				if ($nuts [15] == $values) {
					$aineÜhikuKaalud [$key] = intval ( ($nuts [14]) ) / 100;
				}
				// echo 'Aine andmed: ';
				// var_dump($nuts)."</br>";
			} else {
				$aineÜhikuKaalud [$key] = 0.01;
			}
		}
		
		foreach ( $aineKaalud as $key => $values ) {
			$aineKogus [] = $aineKaalud [$key] * $aineÜhikuKaalud [$key];
		}
		
		/*
		 * echo 'Nimed: ';
		 * var_dump($aineNimed)."</br>";
		 * echo 'Kogused: ';
		 * var_dump($aineKaalud)."</br>";
		 * echo 'Ühikud: ';
		 * var_dump($aineÜhikud)."</br>";
		 * echo 'Ühikute kaalud: ';
		 * var_dump($aineÜhikuKaalud)."</br>";
		 * echo 'Kokku: ';
		 * var_dump($aineKogus)."</br>";
		 */
		
		foreach ( $aineNimed as $key => $values ) {
			$nutsCheck = mysql_query ( "SELECT * FROM ToiduAined WHERE Kirjeldus=\"" . $values . "\";" ) or die ( mysql_error () );
			
			while ( $nutArr = mysql_fetch_array ( $nutsCheck ) ) {
				$water [$key] = $nutArr ['Water (g)'] * $aineKogus [$key];
				$energy [$key] = $nutArr ['Energ Kcal'] * $aineKogus [$key];
				$protein [$key] = $nutArr ['Protein (g)'] * $aineKogus [$key];
				$carbohydrt [$key] = $nutArr ['Carbohydrt (g)'] * $aineKogus [$key];
				$sugar [$key] = $nutArr ['Sugar Tot (g)'] * $aineKogus [$key];
				$sodium [$key] = $nutArr ['Sodium (mg)'] * $aineKogus [$key];
				$fa_sat [$key] = $nutArr ['FA Sat (g)'] * $aineKogus [$key];
				$fa_mono [$key] = $nutArr ['FA Mono (g)'] * $aineKogus [$key];
				$fa_poly [$key] = $nutArr ['FA Poly (g)'] * $aineKogus [$key];
				$cholestr [$key] = $nutArr ['Cholestrl (mg)'] * $aineKogus [$key];
			}
		}
		$kaal = array_sum ( $aineKogus ) * 100;
		
		echo "<h2>" . stripslashes ( $nimi ) . "</h2><p></p>
		<p>" . stripslashes ( $kirjeldus ) . "
		<ul id = 'nutritions'>
		<li class = 'nutRow'>Total weight <span>" . round ( $kaal, 1 ) . " g</span></li>		
		<li class = 'nutRow'>Kcal <span>" . round ( array_sum ( $energy ), 1 ) . " kcal</span></li>
		<li class = 'nutRow'>Proteins <span>" . round ( array_sum ( $protein ), 1 ) . " g</span></li>
		<li class = 'nutRow'>Carbohydrates <span>" . round ( array_sum ( $carbohydrt ), 1 ) . " g</span></li>
        <li class = 'nutRow'>Saturated fat <span>" . round ( array_sum ( $fa_sat ), 1 ) . " g</span></li>
		<li class = 'nutRow'>Monounsaturated fat <span>" . round ( array_sum ( $fa_mono ), 1 ) . " g</span></li>
		<li class = 'nutRow'>Polyunsaturated fat <span>" . round ( array_sum ( $fa_poly ), 1 ) . " g</span></li>
		<li class = 'nutRow'>Sugar <span>" . round ( array_sum ( $sugar ), 1 ) . " g</span></li>
		<li class = 'nutRow'>Sodium <span>" . round ( array_sum ( $sodium ), 1 ) . " mg</span></li>		
		<li class = 'nutRow'>Cholesterol <span>" . round ( array_sum ( $cholestr ), 1 ) . " mg</span></li>
        <li class = 'nutRow'>Water <span>" . round ( array_sum ( $water ), 1 ) . " ml</span></li>
		</ul>";
	}
}
?>