<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
    'title' => "Übersicht Wachen",
    'secured' => true,
);

if (isset ( $_POST ['delete'] )) {
	$delete_event_uuid = trim ( $_POST ['delete'] );
	mail_delete_event ( $delete_event_uuid );
	if( $eventController->markAsDeleted( $delete_event_uuid ) ){
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventDeleted, $delete_event_uuid));
		$variables ['successMessage'] = "Wache gelöscht";
	} else {
		$variables ['alertMessage'] = "Wache konnte nicht gelöscht werden";
	}
}

if(userLoggedIn()){
	$currentUser = $userController->getCurrentUser();
    
	if($currentUser->hasPrivilegeByName(Privilege::FFADMINISTRATION)){
		$events = $eventDAO->getActiveEvents();
		$pastEvents = $eventDAO->getPastEvents();
	} else {
		$publicEvents = $eventDAO->getPublicEvents();
		$engineEvents = $eventDAO->getUsersActiveEvents($currentUser);
	    $events = array_merge($engineEvents, $publicEvents);
	    
	    $pastEvents = $eventDAO->getUsersPastEvents($currentUser);

	}
	$variables ['events'] = $events;
	$variables ['pastEvents'] = $pastEvents;
}


renderLayoutWithContentFile ($config["apps"]["guardian"], "eventOverview_template.php", $variables );
?>