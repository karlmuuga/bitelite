<?php
$connect = mysql_connect ( '192.3.95.138', 'PHP', '@Z672&I0HO$7PcP@xLPugHyY*17Yb5' );
if (! $connect) {
	die ( 'Could not connect: ' . mysql_error () );
}

$db_selected = mysql_select_db ( "bitelite" );
if (! $db_selected) {
	die ( ' Could not select database: ' . mysql_error () );
}
mysql_set_charset ( 'utf8', $connect );
?>