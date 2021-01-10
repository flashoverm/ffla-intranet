<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );

function createUUID(){
	mt_srand ( ( double ) microtime () * 10000 ); // optional for php 4.2.0 and up.
	$charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
	$hyphen = chr ( 45 ); // "-"
	$uuid = substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 );
	return $uuid;
}

if(isset($_GET["num"])){
    for($i=0; $i<$_GET["num"]; $i++){
    	echo createUUID () . "<br>";
    }
    
} else {
    
    for($i=0; $i<100; $i++){
    	echo createUUID () . "<br>";
    }
}