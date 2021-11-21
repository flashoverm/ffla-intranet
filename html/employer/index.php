<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations" ); // redirects

?>