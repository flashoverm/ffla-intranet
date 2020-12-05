<?php

$db = new mysqli ( $config ['db'] ['host'], $config ['db'] ['username'], $config ['db'] ['password'], $config ['db'] ['dbname'] );
$db->set_charset('utf8');

if ($db->connect_error) {
	die ( 'Connect Error (' . $db->connect_errno . ') ' . $db->connect_error );
}

function getGUID() {
	if (function_exists ( 'com_create_guid' )) {
		return com_create_guid ();
	} else {
		mt_srand ( ( double ) microtime () * 10000 ); // optional for php 4.2.0 and up.
		$charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
		$hyphen = chr ( 45 ); // "-"
		$uuid = substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 );
		return $uuid;
	}
}

?>