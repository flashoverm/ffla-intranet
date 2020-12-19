<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

session_start ();

if($userController->getCurrentUser()->hasPrivilegeByName(Privilege::FFADMINISTRATION)){
	header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations/process" ); // redirects
} else {
	header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations" ); // redirects
}

?>