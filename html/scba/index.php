<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
header ( "Location: " . $config["urls"]["scbaapp_home"] . "/overview" ); // redirects
?>