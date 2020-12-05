<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

session_start ();

if(current_user_has_privilege(FILEADMIN)){
	header ( "Location: " . $config["urls"]["filesapp_home"] . "/forms/admin" ); // redirects
} else {
	header ( "Location: " . $config["urls"]["filesapp_home"] . "/forms" ); // redirects
}

?>