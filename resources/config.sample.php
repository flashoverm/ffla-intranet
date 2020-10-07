<?php

//DONT FORGET TO CHANGE config.sample.php

$url_prefix = "";

$config = array (
    "db" => array (
        "dbname" => "ffintranet",
        "username" => "ffintranet",
        "password" => "xxxxxxxxxx",
        "host" => "localhost"
    ),
    "mail" => array (
        "host" => "xxxxxxxxxx",
        "username" => "xxxxxxxxxx",
        "password" => "xxxxxxxxxx",
        "secure" => "ssl",
        "port" => 465,
        "fromaddress" => "intranet@feuerwehr-landshut.de",
    	"fromname" => "Intranet Feuerwehr Landshut"
    ),
    "apps" => array(
        "landing" => "",
        "files" => "filesapp",
        "hydrant" => "hydrantapp",
		"guardian" => "guardianapp",
    	"employer" => "employerapp",
	),
    "urls" => array (
    	"intranet_home" => $url_prefix . "",
    	"filesapp_home" => $url_prefix . "/files",
    	"hydrantapp_home" => $url_prefix . "/hydrant",
    	"guardianapp_home" => $url_prefix . "/guardian",
    	"employerapp_home" => $url_prefix . "/employer",
    		
    	"files" => $url_prefix . "/files",
        
    	"base_url" => "http://127.0.0.1",
    ),
	"paths" => array (
		"resources" => $_SERVER ['DOCUMENT_ROOT'] . "/../resources/",
		"images" => array (
				"content" => $_SERVER ["DOCUMENT_ROOT"] . "/images/content",
				"layout" => $_SERVER ["DOCUMENT_ROOT"] . "/images/layout/"
		),
		"files" => $_SERVER ["DOCUMENT_ROOT"] . "/../resources/files/",
		"maps" => $_SERVER ["DOCUMENT_ROOT"] . "/../resources/maps/",
		"inspections" => $_SERVER ["DOCUMENT_ROOT"] . "/../resources/inspections/",
		"reports" => $_SERVER ["DOCUMENT_ROOT"] . "/../resources/reports/",
		"nodejs" => "D:/runtimes/nodejs/node.exe"
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
	"settings" => array (
		"reminderAtDay" => 10,                  //days before "not-full-reminder" is sent
		"selfregistration" => false,                    //enables self registration of managers
		"autoadmin" => true,                                   //manager is always admin
		"reportfunction" => true,                               //enalbes function to create event report
		"publicevents" => true,                                 //enables list of public events
		"staffconfirmation" => true,
		
		"enginemgrmailonsubscription" => false,  //Send mail to all managers of the users engine  on subscription
		"creatormailonsubscription" => true,    //Send mail to creator of the event (if event is full, a mail is always sent)
		"usermailonsubscription" => true,               //Send mail to subscribing user on subscribing a event
		"useDefaultMapMarker" => false,
		"deactivateOutgoingMails" => false,
	)
);

$hydrant_criteria = array (
    array(0, "Adresse in Ordnung"),
    array(1,"Kein Mangel"),
    array(2,"Hinweisschild fehlt"),
    array(3,"Hinweisschild verdeckt<br> (Grund s. Hinweise)"),
    array(4,"Hinweisschild: Angaben stimmen nicht<br> (Richtige Angabe s. Hinweise)"),
    array(5,"Deckel lässt sich nicht öffnen"),
    array(6,"Deckel defekt"),
    array(7,"Deckelgelenk defekt"),
	array(8,"Schmutzsicherung Gummi defekt/fehlt"),
	array(9,"Hydrant innen erheblich verschmutzt<br> Reiningung erforderlich"),
    array(10,"Hydrant undicht (Wasser im Schacht)"),
    array(11,"Schutzkappte fehlt"),
    array(12,"Ventilspindel lässt sich nicht drehen"),
    array(13,"Klaue abgebrochen"),
    array(14,"Standrohr kann nicht aufgesetzt werden"),
    array(15,"Hydrant entleert nicht"),
    array(16,"Ventilspindel geht leer durch"),
    array(17,"Vierkant abgebrochen"),
    array(18,"Hydrant lässt sich nicht dicht schließen"),
    array(19,"Hydrant nicht gefunden"),
    array(20,"Hydrant überteert"),
);


//Mailing Lists
define("INSPECTIONREPORT", "INSPECTIONREPORT");



defined ( "LIBRARY_PATH" ) or define ( "LIBRARY_PATH", realpath ( dirname ( __FILE__ ) . '/library' ) );

defined ( "TEMPLATES_PATH" ) or define ( "TEMPLATES_PATH", realpath ( dirname ( __FILE__ ) . '/templates' ) );


/*
 * Error reporting.
 */
ini_set ( "error_reporting", "true" );
error_reporting ( E_ALL | E_STRCT );

?>