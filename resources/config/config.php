<?php

$inc_result = include_once 'instanceConfig.php';

if( ! $inc_result){
    echo "<h1>Error</h1><p>instanceConfig.php not found - please define the neccessary instance-specific configuration</p>";
    die();
}

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
    	"administration" => "administrationapp",
    	"users" => "usersapp",
        "files" => "filesapp",
        "hydrant" => "hydrantapp",
        "guardian" => "guardianapp",
        "employer" => "employerapp",
        "masterdata" => "masterdataapp",
        "pager" => "pagerapp"
    ),
    "urls" => array (
        'url_prefix' => $url_prefix,
        
        "intranet_home" => $url_prefix . "",
        "filesapp_home" => $url_prefix . "/files",
        "hydrantapp_home" => $url_prefix . "/hydrant",
        "guardianapp_home" => $url_prefix . "/guardian",
        "employerapp_home" => $url_prefix . "/employer",
        "masterdataapp_home" => $url_prefix . "/masterdata",
        "pagerapp_home" => $url_prefix . "/pager",
        
        "files" => $url_prefix . "/files",
        
        "base_url" => "https://intranet.feuerwehr-landshut.de",
    ),
    
    "paths" => array (
        "data" => dirname ( __FILE__ ) . "/../../data/",
        "initial" => dirname ( __FILE__ ) . "/../../data/_initial/",
        "files" => dirname ( __FILE__ ) . "/../../data/files/",
        "maps" => dirname ( __FILE__ ) . "/../../data/maps/",
        "inspections" => dirname ( __FILE__ ) . "/../../data/inspections/",
        "reports" => dirname ( __FILE__ ) . "/../../data/reports/",
        "confirmations" => dirname ( __FILE__ ) . "/../../data/confirmations/",
        "backup" => dirname ( __FILE__ ) . "/../../data/backup/",
        "nodejs" => "nodejs",
        "php" => "/usr/bin/php"
    ),
    "formats" => array (
        "date" => "d.m.Y",
        "time" => "H:i",
        "datetime" => "d.m.Y H:i",
    	"sqldatetime" => "'%d.%m.%Y %T'",
    	"sqldate" => "'%d.%m.%Y'",
    	"sqltime" => "'%T'",
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
        "locationId" => 2, //1 = US, 2 = EU
        "username" => "",
        "password" => "",
    ),
    //default settings, can be overwritten in instanceConfig
    "settings" => array (
        "eventHourlyRate" => array(
            2022 => 15.90,
            2024 => 16.40
        ),
        "reminderAtDay" => 10,						//days before "not-full-reminder" is sent
        "reportReminderAfterDays" => 14,            //days after reminder is sent to approve report
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

if( ! isset($dbConfig) || ! isset($mailConfig)){
    echo "<h1>Error</h1><p>Database and/or mail configuration not defined in instanceConfig.php - please define the neccessary instance-specific configuration</p>";
    die();
}

$config["db"] = $dbConfig;
$config["mail"] = $mailConfig;

$config = overrideConfig($config, $overrideConfig);

//Mailing Lists
define("INSPECTIONREPORT", "INSPECTIONREPORT");

$mailingList = array(
    "INSPECTIONREPORT" => array(
        "hydranten@feuerwehr-landshut.de",
        "d.hammerl@stadtwerke-landshut.de",
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
//Remove on PROD
error_reporting ( E_ALL | E_STRICT );

?>