<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );

header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/inspection/overview" ); // redirects
?>