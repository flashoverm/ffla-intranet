<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
    'title' => "Admin-Übersicht Wachen",
    'secured' => true,
	'privilege' => EVENTADMIN
);
    
if (isset ( $_POST ['delete'] )) {
	$delete_event_uuid = trim ( $_POST ['delete'] );
	if(mark_event_as_deleted ( $delete_event_uuid, $_SESSION ['intranet_userid'] )){
		insert_log(LogbookActions::EventDeleted, $delete_event_uuid);
		$variables ['successMessage'] = "Wache gelöscht";
	} else {
		$variables ['alertMessage'] = "Wache konnte nicht gelöscht werden";
	}
}
    
if (isset ( $_POST ['deletedb'] )) {
	$delete_event_uuid = trim ( $_POST ['deletedb'] );
	if(delete_event ( $delete_event_uuid )){
		insert_log(LogbookActions::EventDeletedDB, $delete_event_uuid);
		$variables ['successMessage'] = "Wache aus Datenbank gelöscht";
	} else {
		$variables ['alertMessage'] = "Wache konnte nicht gelöscht werden";
	}
}
    
$events = get_all_active_events ();
$pastEvents = get_all_past_events();
$deletedEvents = get_all_deleted_events();
$variables ['events'] = $events;
$variables ['pastEvents'] = $pastEvents;
$variables ['deletedEvents'] = $deletedEvents;

renderLayoutWithContentFile ($config["apps"]["guardian"], "eventAdmin_template.php", $variables );
?>