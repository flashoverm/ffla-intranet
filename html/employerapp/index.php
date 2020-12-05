<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

session_start ();

if(current_user_has_privilege(FFADMINISTRATION)){
	header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations/process" ); // redirects
} else {
	header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations" ); // redirects
}

?>