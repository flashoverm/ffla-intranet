<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . "/db_logbook.php";
require_once LIBRARY_PATH . "/db_inspection.php";


function userEntry($action, $user_uuid){
	global $logbookActions;
	$user = get_user($user_uuid);
	return $logbookActions[$action] . ": " . $user->firstname . " " . $user->lastname . " (" . $user->email . ")";
}

function loginEntry($action, $user_uuid){
	global $logbookActions;
	$user = get_user($user_uuid);
	return $logbookActions[$action] . ": " . $user->firstname . " " . $user->lastname . " (" . $user->email . ")";
}

function mailEntry($action, $mail_uuid){
	
}

function eventEntry($action, $event_uuid){
	global $logbookActions, $config;
	$event = getEvent($event_uuid);
	return $logbookActions[$action] . ":<br>" 
			. "Wache: " . $event->type . " (" . date($config ["formats"] ["date"], strtotime($event->date)) . ")<br>"
			. "Titel: " . $event->title ;
}

function eventStaffEntry($action, $staff_uuid){
	global $logbookActions, $config;;
	$staff = get_events_staffposition($staff_uuid);
	$staffpos = get_staffposition($staff->position);
	$event = getEvent($staff->event);
	$user = getUser($staff->user);
	return $logbookActions[$action] . ":<br>" 
			. "Wache:  " . $event->type . " - " . date($config ["formats"] ["date"], strtotime($event->date)) . " (" . $staffpos->position . ") <br>"
			. "Person: " . $user->firstname . " " . $user->lastname . " (" . $user->email . ")";
}

function staffTemplateEntry($action, $eventtype_uuid){
	global $logbookActions;
	return $logbookActions[$action];
}

function eventReportEntry($action, $report_uuid){
	global $logbookActions, $config;;
	$report = getReport($report_uuid);
	return $logbookActions[$action] . ":<br>"
			. "Bericht für: " . $report->type . " (" . date($config ["formats"] ["date"], strtotime($report->date)) . ")<br>"
					. "Titel: " . $report->title ;
}

function eventReportExport($action){
	global $logbookActions;
	return $logbookActions[$action];
}

function hydrantEntry($action, $hydrant_uuid){
	global $logbookActions;
	$hydrant = get_hydrant_by_uuid($hydrant_uuid);
	return $logbookActions[$action] . ": " . $hydrant->hy;
}

function hydrantInspectionEntry($action, $inspection_uuid){
	global $logbookActions, $config;;
	$inspection = get_inspection($inspection_uuid);
	return $logbookActions[$action] . ": " . $inspection->vehicle . " (" . date($config ["formats"] ["date"], strtotime($inspection->date)) . ")";
}

function fileEntry($action, $file_uuid){
	global $logbookActions;
	$file = get_file($file_uuid);
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
		return eventReportExport($action_id);
		
	} else if ($action_id < 200){
		return eventReportEntry($action_id, $object_uuid);
		
	} else if ($action_id < 210){
		return hydrantEntry($action_id, $object_uuid);
		
	} else if ($action_id < 220){
		return hydrantInspectionEntry($action_id, $object_uuid);
		
	} else if ($action_id < 310){
		return fileEntry($action_id, $object_uuid);
	}
}


?>
