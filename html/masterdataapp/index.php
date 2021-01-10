<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

session_start ();

if($userController->hasCurrentUserPrivilege(Privilege::MASTERDATAADMIN)){
	header ( "Location: " . $config["urls"]["masterdataapp_home"] . "/datachangerequests/process" ); // redirects
} else {
	header ( "Location: " . $config["urls"]["masterdataapp_home"] . "/datachangerequests" ); // redirects
}

?>