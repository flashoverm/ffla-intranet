<?php
require_once realpath(__DIR__ . "/../../resources/bootstrap.php");

header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/search" ); // redirects
?>