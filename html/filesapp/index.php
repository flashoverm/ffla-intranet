<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

if($userController->hasCurrentUserPrivilege(Privilege::FILEADMIN)){
	header ( "Location: " . $config["urls"]["filesapp_home"] . "/forms/admin" ); // redirects
} else {
	header ( "Location: " . $config["urls"]["filesapp_home"] . "/forms" ); // redirects
}

?>