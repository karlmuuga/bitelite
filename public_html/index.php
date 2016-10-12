<?php session_start();/*require 'php/captcha.php';$captcha = new ResponsiveCaptcha()*/;require_once("php/validate.php");?>
<!DOCTYPE html>
<html>
<head>
<META NAME="author" CONTENT="Karl-Heinrich Tamm and Karl-Hendrik Muuga">
<META NAME="subject" CONTENT="Restaurants">
<META NAME="Description"
	CONTENT="BiteLite allows restaurant owners to log their meals, so customers can examine with nutritional value.">
<META NAME="Classification"
	CONTENT="Visitors can search various eating places, have a look on a basic information about certain restaurant, tell what they think of diner at the commenting section on its personal BiteLite page and examine with the nutritional value in diner meals. Diner owners can create account for their restaurant, add basic information about their restaurant, log all their meals etc.">
<META NAME="Keywords"
	CONTENT="customer,search,various,eating,places,tell,what,you,think,of,diner,at,the,commenting,section,on,its,personal,bitelite,page,have,a,look,on,a,basic,information,about,certain,restaurant,examine,with,the,nutritional,value,in,diner,meals,diner,owner,create,account,for,your,restaurant,add,basic,information,about,your,restaurant,log,all,your,meals,later,edit,previously,added,meals,">
<META NAME="Language" CONTENT="English">
<META NAME="Copyright" CONTENT="BiteLite">
<META NAME="Designer" CONTENT="Karl-Heinrich Tamm">
<META NAME="Publisher" CONTENT="BiteLite">
<META NAME="distribution" CONTENT="Global">
<META NAME="zipcode" CONTENT="74001">
<META NAME="city" CONTENT="Viimsi">
<META NAME="country" CONTENT="Estonia">
<meta charset='utf-8'>
<title>BiteLite</title>
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="icon" type="image/png" href="img/favicon.png">
<link href='http://fonts.googleapis.com/css?family=Open+Sans'
	rel='stylesheet' type='text/css'>
<link rel='stylesheet' href='css/index.css'>
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
			<li><a href="#search">Search</a></li>
			<li><a href="#overview">Overview</a></li>
			<li><a href="#account">Account</a></li>
			<!-- <li><a href="#faq">FAQ</a></li> -->
			<li><a href="#contact">Contact</a></li>
		</ul>
		<select onchange="window.location.href=this.value;">
			<option disabled selected="selected">Navigation</option>
			<option value='#search'>Search</option>
			<option value='#overview'>Overview</option>
			<option value='#account'>Account</option>
			<!-- <option value = '#faq'>FAQ</option> -->
			<option value='#contact'>Contact</option>
		</select>
	</nav>
	<!-- End -->
	<div id='main'>
		<!-- Section1 -->
		<div id='search' class='section'>
			<div class='contentDiv'>
				<div id='logoDiv'>
					<img id='logo' src='img/logo.png'>
					<h1>BiteLite</h1>
				</div>
				<form id='searchForm' method='GET'>
					<input type='text' id='searchInput' name='searchInput'
						placeholder="For example, type here Your favourite diner name, address, phone number or descriptive words">
					<button type='submit' id='searchButton' name='searchButton'
						value='Search'>
						<img src='img/icons/search.png' alt='Search'>
					</button>
				</form>
				<div id='searchResults'></div>
			</div>
		</div>
		<!-- End -->
		<!-- Section2 -->
		<div id='overview' class='section'>
			<div class='contentDiv'>
				<h2>Quick overview</h2>
				<h3>If You are a customer, You can:</h3>
				<ul>
					<li>search various eating places.</li>
					<li>tell what You think of diner at the commenting section on its
						personal BiteLite page.</li>
					<li>have a look on information about certain restaurant.</li>
					<li>examine with the nutritional value in diner meals.</li>
				</ul>
				<h3>If You are diner owner, You can:</h3>
				<ul>
					<li>create account for Your restaurant.</li>
					<li>add basic information about Your restaurant.</li>
					<li>log Your meals.</li>
					<li>later edit previously added meals.</li>
				</ul>
			</div>
		</div>
		<!-- Section3 -->
		<div id='account' class='section'>
			<div class='contentDiv'>
				<form id='loginForm' method='POST'>
					<h2 class='accountHeader'>Login</h2>
					<input id='loginEmail' name='loginEmail' type='email'
						placeholder='Email'> <input id='loginPassowrd'
						name='loginPassword' type='password' placeholder='Password'> <input
						id='loginSubmit' name='loginSubmit' type='submit' value='Login'> <input
						id='forgotPass' name='forgotPass' type='submit'
						value='I forgot my password'>
<?php echo $login_error_message;?>
</form>
				<form id='registerForm' method='POST'>
					<h2 class='accountHeader'>Register</h2>
					<p id='registerNB'>This is only meant for diners owners!</p>
					<input id='registerEmail' name='registerEmail' type='email'
						placeholder='Email'> <input id='registerRestaurantName'
						name='restaurantName' type='text' placeholder="Diner name">
					<!-- <input type="text" name="captcha" id="captcha-field" value = 'Captcha: <?php // echo $captcha->getNewQuestion() ?>'/>
<?php // echo $error;?> -->
					<button id='registerSubmit' name='registerSubmit' type='submit'>Create
						a new account</button>
					<span class='terms'>By registering, You agree to the <a
						href='/terms' target="_blank">Terms of Use</a> and <a
						target="_blank" href='/privacy'>Privacy Policy</a>.
					</span>
				</form>
			</div>
		</div>
		<!-- End -->
		<!-- Section4 -->
		<!--
<div id = 'faq' class = 'section'>
<div class = 'contentDiv'>
<h2>FAQ</h2>
</div>
</div>
-->
		<!-- End -->
		<!-- Section5 -->
		<div id='contact' class='section'>
			<div class='contentDiv'>
				<h2>Contact</h2>
				<h3>BITELITE LP</h3>
				<h4>Business ID: 12521402</h4>
				<h4>Country: Estonia</h4>
				<h4>Email: help@mail.bitelite.org</h4>
				<span class='terms'>By using this site, You agree to the <a
					href='/terms' target="_blank">Terms of Use</a> and <a
					target="_blank" href='/privacy'>Privacy Policy</a>. Copyright ©
					2013 BITELITE All Rights Reserved.
				</span>
			</div>
		</div>
		<!-- End -->
		<!-- Navigation -->
	</div>
	<!-- End -->
	<script
		src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script
		src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="js/index.js"></script>
	<script src="js/top.js"></script>
</body>
</html>