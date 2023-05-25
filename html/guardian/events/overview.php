<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["guardian"],
		'template' => "eventOverview_template.php",
	    'title' => "Übersicht Wachen",
	    'secured' => true,
);
checkSitePermissions($variables);

	
if( isset( $_GET["past"] ) ){
	$variables ['tab'] = 'past';
} else if ( isset($_GET["canceled"])) {
    $variables ['tab'] = 'canceled';
} else if ( isset($_GET["subscribed"])) {
    $variables ['tab'] = 'subscribed';
} else {
	$variables ['tab'] = 'current';
}

if($currentUser->hasPrivilegeByName(Privilege::FFADMINISTRATION)){
	if($variables ['tab'] == 'past'){
		$variables ['events'] = $eventDAO->getPastEvents($_GET);
	} else if ($variables ['tab'] == 'canceled') {
	    $variables ['events'] = $eventDAO->getCanceledEvents($_GET);
	} else if ($variables ['tab'] == 'subscribed') {
	    $variables ['events'] = $eventDAO->getUsersSubcribedEvents($currentUser, $_GET);
	} else {
		$variables ['events'] = $eventDAO->getActiveEvents($_GET);
	}
} else {
	if($variables ['tab'] == 'past'){
		$variables ['events'] = $eventDAO->getUsersPastEvents($currentUser, $_GET);
	} else if ($variables ['tab'] == 'canceled') {
	    $variables ['events'] = $eventDAO->getUsersCanceledEvents($currentUser, $_GET);
	} else if ($variables ['tab'] == 'subscribed') {
	    $variables ['events'] = $eventDAO->getUsersSubcribedEvents($currentUser, $_GET);
	} else {
		$variables ['events'] = $eventDAO->getUsersActiveEvents($currentUser, $_GET);
	}
}

renderLayoutWithContentFile ($variables );
?>