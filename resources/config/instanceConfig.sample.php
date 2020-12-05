<?php

//DONT FORGET TO CHANGE instanceConfig.sample.php

//optional, e.g. /test
$url_prefix = "";

//required
$dbConfig = array (
	"dbname" => "ffintranet",
	"username" => "ffintranet",
	"password" => "xxxxxxxxxx",
	"host" => "localhost"
);

//required
$mailConfig = array (
	"host" => "xxxxxxxxxx",
	"username" => "xxxxxxxxxx",
	"password" => "xxxxxxxxxx",
	"secure" => "ssl",
	"port" => 465,
	"fromaddress" => "intranet@feuerwehr-landshut.de",
	"fromname" => "Intranet Feuerwehr Landshut"
);

//optional - overrides default settings
$overrideSettings = array(
	"deactivateOutgoingMails" => true,
	"selfregistration" => false,                    //enables self registration of managers
);
