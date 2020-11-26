<?php
require_once realpath(__DIR__ . "/../resources/bootstrap.php");

session_start ();
session_destroy ();

header("Location: " . $config["urls"]["intranet_home"] . "/login"); // redirects
?>