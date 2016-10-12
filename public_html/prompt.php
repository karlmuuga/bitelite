<?php
session_start ();
require ("php/connect.php");
$x = $_GET ['x'];
function createMessage($x) {
	if (is_numeric ( $x )) {
		switch ($x) {
			case 1 :
				$message = "Thank You for registering! 
						An email with Your password has been sent to Your email.";
				break;
			case 2 :
				$message = "That email address has aleady been registered.";
				break;
		}
		
		echo $message;
	} else {
		echo $x;
	}
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<title>BiteLite - Prompt</title>
<link rel="icon" type="image/png" href="img/favicon.png">
<style>
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p,
	blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn,
	em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var,
	b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend,
	table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas,
	details, embed, figure, figcaption, footer, header, hgroup, menu, nav,
	output, ruby, section, summary, time, mark, audio, video {
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
	margin: 0;
	padding: 0
}

article, aside, details, figcaption, figure, footer, header, hgroup,
	menu, nav, section {
	display: block
}

body {
	line-height: 1
}

ol, ul {
	list-style: none
}

blockquote, q {
	quotes: none
}

blockquote:before, blockquote:after, q:before, q:after {
	content: none
}

table {
	border-collapse: collapse;
	border-spacing: 0
}

body {
	position: absolute;
	width: 100%;
	height: 100%;
	background: #f96
}

* {
	box-sizing: border-box;
	-moz-box-sizing: border-box
}

#container {
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

input {
	width: 30%;
	margin: 15px auto;
	display: block;
	border-radius: 5px;
	height: 40px;
	border: 1px solid #d8d8d8;
	font-size: 1rem;
	padding: 10px;
	text-align: center;
	background: #f2f2f2;
	cursor: pointer;
	outline: 0
}

input:active {
	background: #f96 !important;
	box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.5), 0 1px 0
		rgba(255, 255, 255, 0.8);
	-moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.5), 0 1px 0
		rgba(255, 255, 255, 0.8);
	-webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.5), 0 1px 0
		rgba(255, 255, 255, 0.8);
	outline: 0
}
</style>
</head>
<body>
	<div id='container'>
<?php createMessage($x); ?>
<input type="button" value="Back to previous page"
			onclick='history.go(-1);' />
	</div>
</body>
</html>