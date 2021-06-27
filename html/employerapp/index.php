<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

if($userController->hasCurrentUserPrivilege(Privilege::FFADMINISTRATION)){
	header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations/process" ); // redirects
} else {
	header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations" ); // redirects
}

?>