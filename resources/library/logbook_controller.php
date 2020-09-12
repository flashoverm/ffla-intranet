<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . "/db_inspection.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/db_report.php";
require_once LIBRARY_PATH . "/db_staffpositions.php";
require_once LIBRARY_PATH . "/db_files.php";


function userEntry($action, $user_uuid){
	global $logbookActions;
	$user = get_user($user_uuid);
	if( ! $user ){
		return null;
	}
	return $logbookActions[$action] . ": " . $user->firstname . " " . $user->lastname . " (" . $user->email . ")";
}

function loginEntry($action, $user_uuid){
	global $logbookActions;
	$user = get_user($user_uuid);
	if( ! $user ){
		return null;
	}
	return $logbookActions[$action] . ": " . $user->firstname . " " . $user->lastname . " (" . $user->email . ")";
}

function mailEntry($action, $mail_uuid){
	
}

function eventEntry($action, $event_uuid){
	global $logbookActions, $config;
	$event = get_event($event_uuid);
	if( ! $event ){
		return null;
	}
	return $logbookActions[$action] . ":<br>" 
			. "Wache: " . get_eventtype($event->type)->type . " (" . date($config ["formats"] ["date"], strtotime($event->date)) . " " . date($config ["formats"] ["time"], strtotime($event->start_time)) . " Uhr)<br>"
			. "Titel: " . $event->title ;
}

function eventStaffEntry($action, $staff_uuid){
	global $logbookActions, $config;;
	$staff = get_events_staffposition($staff_uuid);
	if( ! $staff ){
		return null;
	}
	$staffpos = $staff->position;
	$event = get_event($staff->event);
	$user = get_user($staff->user);

	return $logbookActions[$action] . ":<br>" 
			. "Wache:  " . get_eventtype($event->type)->type . " (" . date($config ["formats"] ["date"], strtotime($event->date)) . " " . date($config ["formats"] ["time"], strtotime($event->start_time)) . " Uhr) " . $staffpos . "<br>"
			. "Person: " . $user->firstname . " " . $user->lastname . " (" . $user->email . ")";
}

function staffTemplateEntry($action, $eventtype_uuid){
	global $logbookActions;
	if( ! true ){
		return null;
	}
	return $logbookActions[$action];
}

function eventReportEntry($action, $report_uuid){
	global $logbookActions, $config;;
	$report = get_report($report_uuid);
	if( ! $report ){
		return null;
	}
	return $logbookActions[$action] . ":<br>" 
			. "Bericht für: " . get_eventtype($report->type)->type . " (" . date($config ["formats"] ["date"], strtotime($report->date)) . " " . date($config ["formats"] ["time"], strtotime($report->start_time)) . " Uhr)<br>"
			. "Titel: " . $report->title ;
}

function eventReportExport($action){
	global $logbookActions;
	if( ! true ){
		return null;
	}
	return $logbookActions[$action];
}

function hydrantEntry($action, $hydrant_uuid){
	global $logbookActions;
	$hydrant = get_hydrant_by_uuid($hydrant_uuid);
	if( ! $hydrant ){
		return null;
	}
	return $logbookActions[$action] . ": HY-Nr. " . $hydrant->hy;
}

function hydrantInspectionEntry($action, $inspection_uuid){
	global $logbookActions, $config;;
	$inspection = get_inspection($inspection_uuid);
	if( ! $inspection ){
		return null;
	}
	return $logbookActions[$action] . ": " . $inspection->vehicle . " (" . date($config ["formats"] ["date"], strtotime($inspection->date)) . ")";
}

function fileEntry($action, $file_uuid){
	global $logbookActions;
	$file = get_file($file_uuid);
	if( ! $file ){
		return null;
	}
	return $logbookActions[$action] . ": " . $file->description;
}



function logbookEnry($action_id, $object_uuid){
	if($action_id < 20){
		return userEntry($action_id, $object_uuid);
		
	} else if ($action_id < 30){
		return loginEntry($action_id, $object_uuid);
		
	} else if ($action_id < 40){
		return mailEntry($action_id, $object_uuid);
		
	} else if ($action_id < 110){
		return eventEntry($action_id, $object_uuid);
		
	} else if ($action_id < 130){
		return eventStaffEntry($action_id, $object_uuid);
		
	} else if ($action_id < 140){
		return staffTemplateEntry($action_id, $object_uuid);
		
	} else if ($action_id < 190){
		return eventReportEntry($action_id, $object_uuid);
		
	} else if ($action_id < 200){
		return eventReportExport($action_id);
		
	} else if ($action_id < 210){
		return hydrantEntry($action_id, $object_uuid);
		
	} else if ($action_id < 220){
		return hydrantInspectionEntry($action_id, $object_uuid);
		
	} else if ($action_id < 310){
		return fileEntry($action_id, $object_uuid);
	}
}


?>
