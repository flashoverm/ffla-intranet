<?php

//DONT FORGET TO CHANGE instanceConfig.sample.php

//optional, e.g. /test
$url_prefix = "";

//required
$dbConfig = array (
		"dbname" => "fflaintranet",
		"username" => "fflaintranet",
		"password" => "fflaintranet",
		"host" => "localhost"
);

//required
$mailConfig = array (
		"host" => "127.0.0.1",
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
				"base_url" => "http://127.0.0.1"
		),
		"paths" => array(
				"nodejs" => "D:/runtimes/nodejs/node.exe"
		),
		"mapView" => array(
				"apiKey" => "yourapikey",
		),
		"pcloud" => array (
				"username" => "",
				"password" => "",
		),
		"settings" => array(
				"deactivateOutgoingMails" => true,
				"selfregistration" => false,
		)
);
