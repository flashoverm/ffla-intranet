<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once LIBRARY_PATH . "/db_user.php";

session_start ();

if(current_user_has_privilege(FFADMINISTRATION)){
	header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations/process" ); // redirects
} else {
	header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations" ); // redirects
}

?>