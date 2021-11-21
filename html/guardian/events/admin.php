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
$variables = checkPermissions($variables);

if (isset ( $_POST ['delete'] )) {
	$delete_event_uuid = trim ( $_POST ['delete'] );
	if($eventController->markAsDeleted( $delete_event_uuid )){
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventDeleted, $delete_event_uuid));
		$variables ['successMessage'] = "Wache gelöscht";
	} else {
		$variables ['alertMessage'] = "Wache konnte nicht gelöscht werden";
	}
}

if (isset ( $_POST ['deletedb'] )) {
	$delete_event_uuid = trim ( $_POST ['deletedb'] );
	$log = LogbookEntry::fromAction(LogbookActions::EventDeletedDB, $delete_event_uuid);
	if( $eventDAO->deleteEvent($delete_event_uuid)){
		$logbookDAO->save($log);
		$variables ['successMessage'] = "Wache aus Datenbank gelöscht";
	} else {
		$variables ['alertMessage'] = "Wache konnte nicht gelöscht werden";
	}
}

$variables ['events'] = $eventDAO->getActiveEvents();
$variables ['pastEvents'] = $eventDAO->getPastEvents();
$variables ['deletedEvents'] = $eventDAO->getDeletedEvents();

renderLayoutWithContentFile($variables);
?>