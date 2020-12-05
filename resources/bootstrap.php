<?php 
require_once realpath ( dirname ( __FILE__ ) . "/config/config.php" );

foreach (glob( REPOSITORIES_PATH . "/*.php") as $filename) {
	include_once $filename;
}

foreach (glob( LIBRARY_PATH . "/*.php") as $filename) {
	include_once $filename;
}

foreach (glob( REPOSITORIES_PATH . "/*.php") as $filename) {
	include_once $filename;
}

foreach (glob( CONTROLLER_PATH . "/*.php") as $filename) {
	include_once $filename;
}