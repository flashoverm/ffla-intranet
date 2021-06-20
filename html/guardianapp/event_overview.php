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
	
	if( isset( $_GET["past"] ) ){
		$variables ['tab'] = 'past';
	} else {
		$variables ['tab'] = 'current';
	}
    
	if($currentUser->hasPrivilegeByName(Privilege::FFADMINISTRATION)){
		if($variables ['tab'] == 'past'){
			$variables ['events'] = $eventDAO->getPastEvents();
		} else {
			$variables ['events'] = $eventDAO->getActiveEvents();
		}
	} else {
		if($variables ['tab'] == 'past'){
			$variables ['events'] = $eventDAO->getUsersPastEvents($currentUser);
		} else {
			$publicEvents = $eventDAO->getPublicEvents();
			$engineEvents = $eventDAO->getUsersActiveEvents($currentUser);
			$events = array_merge($engineEvents, $publicEvents);
			
			$variables ['events'] = $events;
		}
	}
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "eventOverview_template.php", $variables );
?>