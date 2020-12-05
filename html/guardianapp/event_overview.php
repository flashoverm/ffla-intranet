<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_user.php";


// Pass variables (as an array) to template
$variables = array (
    'title' => "Übersicht Wachen",
    'secured' => true,
);

if (isset ( $_POST ['delete'] )) {
	$delete_event_uuid = trim ( $_POST ['delete'] );
	mail_delete_event ( $delete_event_uuid );
	if(mark_event_as_deleted ( $delete_event_uuid, $_SESSION ['intranet_userid'])){
		insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::EventDeleted, $delete_event_uuid));
		$variables ['successMessage'] = "Wache gelöscht";
	} else {
		$variables ['alertMessage'] = "Wache konnte nicht gelöscht werden";
	}
}

if(userLoggedIn()){
    
	if(current_user_has_privilege(FFADMINISTRATION)){
		$events = get_all_active_events ();
		$pastEvents = get_all_past_events();
	} else {
		$publicEvents = get_public_events();
	    $engineEvents = get_events (getCurrentUserUUID());
	    $events = array_merge($engineEvents, $publicEvents);
	    
	    $pastEvents = get_past_events(getCurrentUserUUID());

	}
	$variables ['events'] = $events;
	$variables ['pastEvents'] = $pastEvents;
}


renderLayoutWithContentFile ($config["apps"]["guardian"], "eventOverview_template.php", $variables );
?>