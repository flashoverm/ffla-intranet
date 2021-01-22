<?php

include_once 'instanceConfig.php';

function overrideConfig($default, $override) {
	foreach( $override as $setting => $value ){
		if( is_array($value) ){
			$default[$setting] = overrideConfig($default[$setting], $value);
		} else {
			$default[$setting] = $value;
		}
	}
	return $default;
}

if( ! isset( $url_prefix )){
	$url_prefix = "";
}

$config = array (
	"apps" => array(
			"landing" => "",
			"files" => "filesapp",
			"hydrant" => "hydrantapp",
			"guardian" => "guardianapp",
			"employer" => "employerapp",
			"masterdata" => "masterdataapp"
	),
	"urls" => array (
			"intranet_home" => $url_prefix . "",
			"filesapp_home" => $url_prefix . "/files",
			"hydrantapp_home" => $url_prefix . "/hydrant",
			"guardianapp_home" => $url_prefix . "/guardian",
			"employerapp_home" => $url_prefix . "/employer",
			"masterdataapp_home" => $url_prefix . "/masterdata",
			
			"files" => $url_prefix . "/files",
			
			"base_url" => "https://intranet.feuerwehr-landshut.de",
	),
	
	"paths" => array (
			"resources" => $_SERVER ['DOCUMENT_ROOT'] . "/../resources/",
			"images" => array (
					"content" => $_SERVER ["DOCUMENT_ROOT"] . "/images/content",
					"layout" => $_SERVER ["DOCUMENT_ROOT"] . "/images/layout/"
			),
			"data" => $_SERVER ["DOCUMENT_ROOT"] . "/../data/",
			"initial" => $_SERVER ["DOCUMENT_ROOT"] . "/../data/_initial/",
			"files" => $_SERVER ["DOCUMENT_ROOT"] . "/../data/files/",
			"maps" => $_SERVER ["DOCUMENT_ROOT"] . "/../data/maps/",
			"inspections" => $_SERVER ["DOCUMENT_ROOT"] . "/../data/inspections/",
			"reports" => $_SERVER ["DOCUMENT_ROOT"] . "/../data/reports/",
			"confirmations" => $_SERVER ["DOCUMENT_ROOT"] . "/../data/confirmations/",
			"backup" => $_SERVER ["DOCUMENT_ROOT"] . "/../data/backup/",
			"nodejs" => "nodejs"
	),
	"formats" => array (
			"date" => "d.m.Y",
			"time" => "H:i",
			"datetime" => "d.m.Y H:i"
	),
	"mapView" => array (
			"apiUrl" => "https://maps.googleapis.com/maps/api/staticmap",
			"apiKey" => "xxxxxxxxxx",
			"height" => 500,
			"width" => 400,
			"widewidth" => 800,
			"retina" => "2",
			"zoom" => 18,   //range [0,20]
			"marker" => "/hydrant.png",
			"maptype" => "roadmap", 	//roadmap, satellite, hybrid, and terrain
			"defaultcoordinates" => "48.5441917,12.1468532",
	),
	"pcloud" => array (
			"username" => "",
			"password" => "",
	),
	//default settings, can be overwritten in instanceConfig
	"settings" => array (
		"reminderAtDay" => 10,						//days before "not-full-reminder" is sent
		"useDefaultMapMarker" => false,
		
		"selfregistration" => false,                    //enables self registration of managers
		"reportfunction" => true,					//enalbes function to create event report
		"publicevents" => true,						//enables list of public events
		"staffconfirmation" => true,
			
		"enginemgrmailonsubscription" => false,		//Send mail to all managers of the users engine  on subscription
		"creatormailonsubscription" => true,		//Send mail to creator of the event (if event is full, a mail is always sent)
		"usermailonsubscription" => true,			//Send mail to subscribing user on subscribing a event
		
		"deactivateOutgoingMails" => false,
	)
);

$config["db"] = $dbConfig;
$config["mail"] = $mailConfig;

$config = overrideConfig($config, $overrideConfig);

//Mailing Lists
define("INSPECTIONREPORT", "INSPECTIONREPORT");

$mailingList = array(
	"INSPECTIONREPORT" => array(
			"hydranten@feuerwehr-landshut.de",
			"markus@thral.de",
	),
);



defined ( "LIBRARY_PATH" ) or define ( "LIBRARY_PATH", realpath ( dirname ( __FILE__ ) . '/../library' ) );

defined ( "MODELS_PATH" ) or define ( "MODELS_PATH", realpath ( LIBRARY_PATH . '/models' ) );
defined ( "CONTROLLER_PATH" ) or define ( "CONTROLLER_PATH", realpath ( LIBRARY_PATH . '/controller' ) );
defined ( "REPOSITORIES_PATH" ) or define ( "REPOSITORIES_PATH", realpath ( LIBRARY_PATH . '/repositories' ) );

defined ( "TEMPLATES_PATH" ) or define ( "TEMPLATES_PATH", realpath ( dirname ( __FILE__ ) . '/../templates' ) );


/*
 * Error reporting.
 */
ini_set ( "error_reporting", "true" );
error_reporting ( E_ALL | E_STRCT );

?>