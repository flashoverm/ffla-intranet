<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/search" ); // redirects
?>