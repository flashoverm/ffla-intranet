<?php

//DONT FORGET TO CHANGE instanceConfig.sample.php

//optional, e.g. /test
$url_prefix = "";

//required
$dbConfig = array (
	"dbname" => "ffintranet_dump",
	"username" => "ffintranet",
	"password" => "ffintranet",
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

//optional - overrides default settings
$overrideSettings = array(
	"deactivateOutgoingMails" => true,
	"selfregistration" => true,                    //enables self registration of managers
);
