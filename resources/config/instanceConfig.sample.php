<?php

//DONT FORGET TO CHANGE instanceConfig.sample.php

//optional, e.g. /test
$url_prefix = "";

//required
$dbConfig = array (
		"dbname" => "fflaintranet",
		"username" => "fflaintranet",
		"password" => "fflaintranet",
		"host" => "database"
);

//required
$mailConfig = array (
		"host" => "mailhog",
		"username" => "",
		"password" => "",
		"secure" => "",
		"port" => 25,
		"fromaddress" => "intranet@feuerwehr-landshut.de",
		"fromname" => "Intranet Feuerwehr Landshut"
);

//partly optional - overrides default settings
$overrideConfig = array(
		"urls" => array(
				"base_url" => "http://fflaintranet.localhost"
		),
		"paths" => array(
				"nodejs" => "node"
		),
		"mapView" => array(
				"apiKey" => "yourapikey",
		),
		"pcloud" => array (
				"username" => "",
				"password" => "",
		),
		"settings" => array(
				"deactivateOutgoingMails" => false,
				"selfregistration" => false,
		)
);
