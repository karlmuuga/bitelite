<?php

session_start (); // ACCOUNT FAIL
require ($_SERVER ['DOCUMENT_ROOT'] . "/php/connect.php");
$error = "";
if (! isset ( $_SESSION ['token'] )) {
	header ( 'Location: http://www.bitelite.org' );
} else {
	
	$token = $_SESSION ['token'];
	$getUser = mysql_query ( "SELECT * FROM `users` WHERE secure='$token' LIMIT 1;" ) or die ( mysql_error () );
	if (mysql_num_rows ( $getUser ) == 0) {
		die ( "You are not an user!" );
	}
	$getUser = mysql_query ( "SELECT * FROM `users` WHERE secure='$token' LIMIT 1;" ) or die ( mysql_error () );
	while ( $row = mysql_fetch_array ( $getUser ) ) {
		$dinerName = $row ['dinerName'];
		$location = $row ['location'];
		$desc = $row ['description'];
		$phone = $row ['phone'];
		$email = $row ['contact_email'];
		$new_email = $row ['email'];
		$id = $row ['user_id'];
	}
	$result = mysql_query ( "SELECT * FROM foods WHERE own='$id';" );
	if (! $result or mysql_num_rows ( $result ) == 0) {
		$error = "<span>You haven't added any dishes!</span>";
	} else {
		$foods = array ();
		$dishNames = array ();
		$dishDescs = array ();
		while ( $foods = mysql_fetch_array ( $result ) ) {
			$dishNames [] = $foods ['dish_name'];
			$dishDescs [] = $foods ['dish_desc'];
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<title>Account</title>
<link rel='stylesheet' href='index.css'>
<link rel='stylesheet' href='/css/apprise.css'>
<link rel="icon" type="image/png" href="/img/favicon.png">
<link href='http://fonts.googleapis.com/css?family=Open+Sans'
	rel='stylesheet' type='text/css'>
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1">
<style>
#noscript {
	background-color: white;
	text-align: center;
	box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.5), 0 1px 0
		rgba(255, 255, 255, 0.8);
	-moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.5), 0 1px 0
		rgba(255, 255, 255, 0.8);
	-webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.5), 0 1px 0
		rgba(255, 255, 255, 0.8);
	font-family: "Arial Narrow", Arial, sans-serif;
	display: block;
	max-width: 90%;
	margin: 150px auto;
	padding: 30px;
	font-size: 2rem;
	border-radius: 4px
}
</style>
</head>
<body>
<?php
$getUser = mysql_query ( "SELECT * FROM `users` WHERE user_id='$id' LIMIT 1;" ) or die ( mysql_error () );
$row = mysql_fetch_array ( $getUser );
if ($row ['isPremium'] == '1') {
	if ($row ['expireDate'] == "") {
		$date = 0;
	} else {
		$date = strtotime ( $row ['expireDate'] );
	}
	if (time () > $date) {
		$result4 = mysql_query ( "UPDATE users SET expireDate='',isPremium='0';" ) or die ( mysql_error () );
		echo '<input type = "hidden" id = "premiumExpired" value = "true"/>';
	}
}

?>
<!-- If JS is turned off -->
	<noscript>
		<p id='noscript'>
			Houston, we've a problem. You have disabled JavaScript in Your web
			browser. Follow these simple instructions to <a
				href="http://www.activatejavascript.org" target="_blank">enable it.</a>
		</p>
		<style>
#main, nav {
	display: none
}
</style>
	</noscript>
	<!-- End -->
	<!-- Navigation -->
	<nav>
		<ul class="nav">
			<li><a href="#restaurantInformation">Info about diner</a></li>
			<li><a href="#addFood">Add new meal</a></li>
			<li><a href="#editMeals">Edit dishes</a></li>
			<li><a href="#settings">Settings</a></li>
			<li><a href="#premium">Premium account</a></li>
			<li><a href="../logout.php">Log out</a></li>
		</ul>
		<select onchange="window.location.href=this.value;">
			<option disabled selected="selected">Navigation</option>
			<option value='#restaurantInformation'>Info about diner</option>
			<option value='#addFood'>Add new meal</option>
			<option value='#editMeals'>Edit dishes</option>
			<option value='#settings'>Settings</option>
			<option value='#premium'>Premium account</option>
			<option value='../logout.php'>Log out</option>
		</select>
	</nav>
	<!-- End -->
	<div id='main'>
		<!-- Section1 -->
		<div id='restaurantInformation' class='section'>
			<div class='contentDiv'>
				<h1>Basic information about <?php echo $dinerName; ?></h1>
				<!-- Description -->
				<h2>How would You describe Your diner (with 1000 characters)?</h2>
				<textarea id='textareaInput' maxlength='1000' class='descInput'
					name=''><?php echo $desc; ?></textarea>
				<button id='desc' type='submit'>Update</button>
				<!-- End -->
				<!-- Email -->
				<h2>What is the email address, where customers can contact?</h2>
				<input maxlength='150' class='descInput' type='text' id='email'
					value="<?php echo $email; ?>">
				<button id='contact_email' type='submit' class='descButton'>Update</button>
				<!-- End -->
				<!-- Phone number -->
				<h2>What is the phone number, where customers can contact?</h2>
				<input maxlength='50' class='descInput' type='text'
					value="<?php echo $phone; ?>">
				<button id='phone' type='submit' class='descButton'>Update</button>
				<!-- End -->
				<!-- Location -->
				<h2>Where is the restaurant located?</h2>
				<input maxlength='200' class='descInput' type='text'
					value="<?php echo $location; ?>">
				<button id='location' type='submit' class='descButton'>Update</button>
			</div>
		</div>
		<!-- End -->
		<!-- Section2 -->
		<div id='addFood' class='section'>
			<form class='contentDiv' id='foodInfo'>
				<h1>Add a new meal</h1>
				<h2>How is this meal called? (You can use a maximum of 100
					characters)</h2>
				<input maxlength='100' type='text' name='mealName' id='mealName'>
				<h2>How does it look like? (You can use a maximum of 700 characters)</h2>
				<textarea maxlength='700' name='mealDesc' id='mealDesc'></textarea>
				<h2>Type of course</h2>
				<select id='mealsCourse' name='mealCourse'>
					<option>Appetizer</option>
					<option>Breakfast</option>
					<option>Dessert</option>
					<option>Drink</option>
					<option>Lunch</option>
					<option>Main course</option>
					<option>Salad</option>
					<option>Soup</option>
				</select>
				<h2>Ingredients</h2>
				<!-- Ingredients -->
				<div id='ingredients'>
					<input type='text' id='addItemSearch'
						placeholder='Type here a name of ingredient, what can be found in Your meal'>
					<div id='foodResults'></div>
					<div id='addedFoods'></div>
				</div>
			</form>
			<div id='addFoodButtonSeparator'></div>
			<input type='submit' id='addFoodButton'
				value='Finish and add a new meal'>
		</div>
		<!-- End -->
		<!-- Section3 -->
		<div id='editMeals' class='section'>
			<div class='contentDiv'>
				<h1>Edit dishes</h1>
				<div id='listOfFoods'>
<?php
if ($error == "") {
	foreach ( $dishNames as $key => $values ) {
		echo "<input readonly class = 'mealToEdit' type = 'text' value = \"" . urldecode ( $values ) . "\">";
	}
} else {
	echo $error;
}
?>
</div>
			</div>
		</div>
		<!-- End -->
		<!-- Section4 -->
		<div id='settings' class='section'>
			<div class='contentDiv'>
				<h1>Settings</h1>
				<h2>Change password</h2>
				<input maxlength='80' class='settingsInput' type='password'
					id='passwordUpdate'>
				<button id='password_change' type='submit' class='settingsButton'>Update</button>
				<input id='showPassword' type='button' value='Show password'>
				<h2>Change email</h2>
				<input maxlength='80' class='settingsInput' type='email'
					id='emailUpdate' value="">
				<button id='password_change' type='submit' class='settingsButton'>Update</button>
			</div>
		</div>
		<!-- End -->
		<!-- Section5 -->
		<div id='premium' class='section'>
<?php

if ($row ['isPremium'] == '0') {
	
	echo '
<div class = "contentDiv">
<h1>Premium account</h1>
<ul id = "regularFeatures">
<li class = "listHead">Basic account features</li>
<li>Add basic information about Your restaurant.</li>
<li>Log Your meals.</li>
<li>Edit previously added meals.</li>
<li>BiteLite search engine uses Your restaurant name.</li>
<li>Can only add maximum of 10 foods.</li>
</ul>
<ul id = "premiumFeatures">
<li class = "listHead">Premium account features</li>
<li>Add basic information about Your restaurant.</li>
<li>Log Your meals.</li>
<li>Edit previously added meals.</li>
<li>BiteLite search engine uses Your restaurant name.</li>
<li class = "premiumFeature">Add unlimited amount of meals.</li>
<li class = "premiumFeature">BiteLite search engine uses Your restaurant address, so visitors could find Your diner more easily.</li>
<li class = "premiumFeature">BiteLite search engine uses Your restaurant description, so visitors could find Your diner more easily.</li>
<li class = "premiumFeature">BiteLite search engine uses Your restaurant phone number, so visitors could find Your diner more easily.</li>
<li class = "premiumFeature">Search result about Your restaurant is displayed right after search button in special section for Premium accounts.</li>
<li class = "premiumFeature">BiteLite page is displayed in golden colours.</li>
<div id="buySeperator"></div>
<form action="https://www.moneybookers.com/app/payment.pl" method="post">
<input type="hidden" name="status_url" value="http://www.bitelite.org/php/premium.php">
<input type="hidden" name="pay_to_email" value="karl_ht@elitemail.org">
<input type="hidden" name="recipient_description" value="BiteLite">
<input type="hidden" name="return_url" value="http://www.bitelite.org/account/">
<input type="hidden" name="return_url_text" value="Return to BiteLite">
<input type="hidden" name="cancel_url" value="http://www.bitelite.org/account/">
<input type="hidden" name="cancel_url_text" value="Return to BiteLite">
<input type="hidden" name="language" value="EN">
<input type="hidden" name="confirmation_note" value="Your purchase has been completed!">
<input type="hidden" name="amount" value="9.99">
<input type="hidden" name="merchant_fields" value="<?php echo $id;?>">
<input type="hidden" name="currency" value="EUR">
<input type="hidden" name="hide_login" value="8">
<input type="hidden" name="detail1_description" value="Enhanced version of regular BiteLite account.">
<input type="hidden" name="detail1_text" value="BiteLite Premium Account">
<button type = "submit" class = "buyButton" type="submit">1 month - 9.99 &euro; or 14 &#36;</button>
</form>
<form action="https://www.moneybookers.com/app/payment.pl" method="post">
<input type="hidden" name="status_url" value="http://www.bitelite.org/php/premium.php">
<input type="hidden" name="pay_to_email" value="karl_ht@elitemail.org">
<input type="hidden" name="recipient_description" value="BiteLite">
<input type="hidden" name="return_url" value="http://www.bitelite.org/account/">
<input type="hidden" name="return_url_text" value="Return to BiteLite">
<input type="hidden" name="cancel_url" value="http://www.bitelite.org/account/">
<input type="hidden" name="cancel_url_text" value="Return to BiteLite">
<input type="hidden" name="language" value="EN">
<input type="hidden" name="confirmation_note" value="Your purchase has been completed!">
<input type="hidden" name="amount" value="19.99">
<input type="hidden" name="merchant_fields" value="<?php echo $id;?>">
<input type="hidden" name="currency" value="EUR">
<input type="hidden" name="hide_login" value="1">
<input type="hidden" name="detail1_description" value="Enhanced version of regular BiteLite account.">
<input type="hidden" name="detail1_text" value="BiteLite Premium Account">
<button type = "submit" class = "buyButton" type="submit">3 months - 19.99 &euro; or 28 &#36;</button>
</form>
<form action="https://www.moneybookers.com/app/payment.pl" method="post">
<input type="hidden" name="status_url" value="http://www.bitelite.org/php/premium.php">
<input type="hidden" name="pay_to_email" value="karl_ht@elitemail.org">
<input type="hidden" name="recipient_description" value="BiteLite">
<input type="hidden" name="return_url" value="http://www.bitelite.org/account/">
<input type="hidden" name="return_url_text" value="Return to BiteLite">
<input type="hidden" name="cancel_url" value="http://www.bitelite.org/account/">
<input type="hidden" name="cancel_url_text" value="Return to BiteLite">
<input type="hidden" name="language" value="EN">
<input type="hidden" name="confirmation_note" value="Your purchase has been completed!">
<input type="hidden" name="amount" value="39.99">
<input type="hidden" name="merchant_fields" value="' . $token . '">
<input type="hidden" name="currency" value="EUR">
<input type="hidden" name="hide_login" value="1">
<input type="hidden" name="detail1_description" value="Enhanced version of regular BiteLite account.">
<input type="hidden" name="detail1_text" value="BiteLite Premium Account">
<button type = "submit" class = "buyButton" type="submit">6 months - 39.99 &euro; or 55 &#36;</button>
</form>
</ul>
<span class = "terms">By using this site, You agree to the <a href = "/terms" target="_blank">Terms of Use</a> and <a target="_blank" href = "/privacy">Privacy Policy</a>. Copyright © 2013 BITELITE All Rights Reserved.</span>
</div>';
} else {
	echo '<p id = "validTill">You have Premium account until ' . $row ['expireDate'] . ' .</p>';
}
?>
</div>
		<!-- End -->
<?php
$db_selected = mysql_select_db ( "bitelite" );
$result = mysql_query ( "SELECT * FROM users WHERE user_id='$id'" ) or die ( mysql_error () );
$arr = mysql_fetch_array ( $result );
if ($arr ['isVerified'] == '0') {
	echo "
<!-- Section6 -->
<div id = 'verification' class = 'section'>
<div class = 'contentDiv'>
<h1>Verification</h1>
<div id = 'step1info'>
<p><span id = 'step1'>Step one!</span> In order to verify Your account, type a address of Your restaurant website to input below and press a button called 'Next step'.</p>
<input type = 'text' placeholder = 'Type here a address of Your restaurant website. Example: www.restaurantname.com' id = 'verificationInput'>
<input type = 'button' value = 'Next step'id = 'verificationButton'>
</div>
<div id = 'step2info'></div>
</div>
</div>
<!-- End -->
";}
?>
<!-- End -->
	</div>
	<script
		src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="index.js"></script>
	<script src="/js/prompt.js"></script>
</body>
</html>