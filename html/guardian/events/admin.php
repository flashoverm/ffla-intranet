<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["guardian"],
		'template' => "eventAdmin_template.php",
	    'title' => "Admin-Übersicht Wachen",
	    'secured' => true,
		'privilege' => Privilege::EVENTADMIN
);
checkSitePermissions($variables);

if( isset( $_GET["past"] ) ){
	$variables ['tab'] = 'past';
	$variables ['events'] = $eventDAO->getPastEvents($_GET);
} else if( isset( $_GET["canceled"] ) ){
	$variables ['tab'] = 'canceled';
	$variables ['events'] = $eventDAO->getCanceledEvents($_GET);
} else {
	$variables ['tab'] = 'current';
	$variables ['events'] = $eventDAO->getActiveEvents($_GET);
}

renderLayoutWithContentFile($variables);
?>