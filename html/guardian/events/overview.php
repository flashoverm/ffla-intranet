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

if (isset ( $_POST ['delete'] )) {
	$delete_event_uuid = trim ( $_POST ['delete'] );
	$event = $eventDAO->getEvent($delete_event_uuid);
	checkPermissions(array(
			array("privilege" => Privilege::EVENTADMIN),
			array("privilege" => Privilege::EVENTMANAGER, "engine" => $event->getEngine()),
			array("user" => $event->getCreator())
	), $variables);
	
	mail_delete_event ( $delete_event_uuid );
	if( $eventController->markAsDeleted( $delete_event_uuid ) ){
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventDeleted, $delete_event_uuid));
		$variables ['successMessage'] = "Wache gelöscht";
	} else {
		$variables ['alertMessage'] = "Wache konnte nicht gelöscht werden";
	}
}

if(SessionUtil::userLoggedIn()){
	
	if( isset( $_GET["past"] ) ){
		$variables ['tab'] = 'past';
	} else {
		$variables ['tab'] = 'current';
	}
    
	if($currentUser->hasPrivilegeByName(Privilege::FFADMINISTRATION)){
		if($variables ['tab'] == 'past'){
			$variables ['events'] = $eventDAO->getPastEvents($_GET);
		} else {
			$variables ['events'] = $eventDAO->getActiveEvents($_GET);
		}
	} else {
		if($variables ['tab'] == 'past'){
			$variables ['events'] = $eventDAO->getUsersPastEvents($currentUser, $_GET);
		} else {
			$variables ['events'] = $eventDAO->getUsersActiveEvents($currentUser, $_GET);
		}
	}
}

renderLayoutWithContentFile ($variables );
?>