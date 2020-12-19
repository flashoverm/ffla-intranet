<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

session_start ();

if($userController->getCurrentUser()->hasPrivilegeByName(Privilege::FILEADMIN)){
	header ( "Location: " . $config["urls"]["filesapp_home"] . "/forms/admin" ); // redirects
} else {
	header ( "Location: " . $config["urls"]["filesapp_home"] . "/forms" ); // redirects
}

?>