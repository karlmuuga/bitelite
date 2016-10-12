<?php 
// DINERI FAIL
require ($_SERVER ['DOCUMENT_ROOT'] . "/php/connect.php");
if (isset ( $_GET ['id'] )) {
	$id = $_GET ['id'];
	$dinerQ = mysql_query ( "SELECT * FROM users WHERE user_id='$id';" ) or die ( mysql_error () );
	if (mysql_num_rows ( $dinerQ ) == 1) {
		$row = array ();
		while ( $row = mysql_fetch_array ( $dinerQ ) ) {
			$dinerName = $row ['dinerName'];
			$id = $row ['user_id'];
			$location = $row ['location'];
			$desc = $row ['description'];
			$phone = $row ['phone'];
			$email = $row ['contact_email'];
			$new_email = $row ['email'];
			if ($row ['isPremium'] == '1') {
				if (time () > strtotime ( $row ['expireDate'] )) {
					$result4 = mysql_query ( "UPDATE users SET expireDate='',isPremium='0';" ) or die ( mysql_error () );
				}
			}
		}
	} else {
		header ( "Location: http://www.bitelite.org" );
	}
} else {
	header ( "Location: http://www.bitelite.org" );
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<title><?php echo $dinerName?></title>
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

.fb-comments, .fb-comments span, .fb-comments iframe {
	width: 100% !important;
}
</style>
</head>
<!-- Navigation -->
<nav>
	<ul class="nav">
		<li><a href="#search">Search</a></li>
		<li><a href="#info">Info about diner</a></li>
		<li><a href="#meals">Meals</a></li>
		<li><a href="#comments">Comments</a></li>
		<li><a href="http://www.bitelite.org/">Back to front page</a></li>
	</ul>
	<select onchange="window.location.href=this.value;">
		<option disabled selected="selected">Navigation</option>
		<option value='#search'>Search</option>
		<option value='#info'>Info about diner</option>
		<option value='#meals'>Meals</option>
		<option value='#comments'>Comments</option>
		<option value='http://www.bitelite.org/'>Back to front page</option>
	</select>
</nav>
<!-- End -->
<body>
<?php
$getUser = mysql_query ( "SELECT * FROM `users` WHERE user_id='$id' LIMIT 1;" ) or die ( mysql_error () );
if (mysql_num_rows ( $getUser ) == 0) {
	die ( "You are not an user!" );
}
$array = mysql_fetch_array ( $getUser );
if ($array ['isPremium'] == '1') {
	echo "<input type = 'hidden' id = 'premiumAccount' value = '1'>";
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
	<div id='main'>
		<!-- Section1 -->
		<div id='search' class='section'>
			<div class='contentDiv'>
<?php echo "<input type= 'hidden' value = '".$id."' id = 'dinerName'>";?>
<form id='searchForm' method="GET">
					<input type='text' id='searchInput' name='searchInput'
						placeholder="Search for another restaurant">
					<button type='submit' id='searchButton' name='searchButton'
						value='Search'>
						<img src='/img/icons/search.png' alt='Search'>
					</button>
				</form>
				<div id='searchResults'></div>
			</div>
		</div>
		<!-- End -->
		<!-- Section2 -->
		<div id='info' class='section'>
			<div class='contentDiv'>
<?php
echo "<h1>" . $dinerName . "</h1><p>" . $desc . "</p>";
if ($email != "") {
	echo "<div class = 'infoHeader'>Diner email address:</div><div class = 'dinerInfo'>" . $email . "</div>";
}
if ($location != "") {
	echo "<div class = 'infoHeader'>Diner location:</div><div class = 'dinerInfo'>" . $location . "</div>";
}
if ($phone != "") {
	echo "<div class = 'infoHeader'>Diner phone number:</div><div class = 'dinerInfo'>" . $phone . "</div>";
}
?>
</div>
		</div>
		<!-- Section3 -->
		<div id='meals' class='section'>
			<div class='contentDiv'>
				<h1>Diner meals</h1>
				<ul id='dinerFoods'>
					<!-- Siia toidu nimed -->
<?php
$result = mysql_query ( "SELECT * FROM foods WHERE own='$id'" );
if (! $result or mysql_num_rows ( $result ) == 0) {
	echo "<li class = 'noFoods'>No food has been added by this restaurant.</li>";
} else {
	while ( $foods = mysql_fetch_array ( $result ) ) {
		$dishNames [] = $foods ['dish_name'];
		$dishCats [] = $foods ['dish_cat'];
	}
	
	foreach ( $dishNames as $key => $values ) {
		echo "<li class = 'foodName' data-foodname = '" . urldecode ( $values ) . "'>" . urldecode ( $values ) . " ($dishCats[$key])</li>";
	}
}
?>
</ul>
				<div id='foodNut'></div>
			</div>
		</div>
		<!-- End -->
		<!-- Section4 -->
		<div id='comments' class='section'>
			<div class='contentDiv'>
				<div id="disqus_thread"></div>
				<script type="text/javascript">
/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
var disqus_shortname = 'bitelite'; // required: replace example with your forum shortname
/* * * DON'T EDIT BELOW THIS LINE * * */
(function() {
var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();
</script>
				<a href="http://disqus.com" class="dsq-brlink">comments powered by <span
					class="logo-disqus">Disqus</span></a>
			</div>
			<span class='terms'>By using this site, You agree to the <a
				href='/terms' target="_blank">Terms of Use</a> and <a
				target="_blank" href='/privacy'>Privacy Policy</a>. Copyright © 2013
				BITELITE All Rights Reserved.
			</span>
		</div>
		<!-- End -->
	</div>
	<script
		src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="index.js"></script>
	<script src="/js/prompt.js"></script>
</body>
</html>