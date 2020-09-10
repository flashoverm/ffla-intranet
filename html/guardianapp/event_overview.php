<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
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
		insert_log(LogbookActions::EventDeleted, $delete_event_uuid);
		$variables ['successMessage'] = "Wache gelöscht";
	} else {
		$variables ['alertMessage'] = "Wache konnte nicht gelöscht werden";
	}
}

if(userLoggedIn()){
    
    $events = get_events ($_SESSION ['intranet_userid']);
    $pastEvents = get_past_events($_SESSION ['intranet_userid']);
    $variables ['events'] = $events;
    $variables ['pastEvents'] = $pastEvents;
}


renderLayoutWithContentFile ($config["apps"]["guardian"], "eventOverview_template.php", $variables );
?>