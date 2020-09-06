<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . "/db_logbook.php";


function userEntry($action, $user_uuid){
	
}

function loginEntry($action, $user_uuid){
	
}

function mailEntry($action, $mail_uuid){
	
}

function eventEntry($action, $event_uuid){
	
}

function eventStaffEntry($action, $staff_uuid){
	
}

function staffTemplateEntry($action, $eventtype_uuid){
	
}

function eventReportEntry($action, $report_uuid){
	
}

function hydrantEntry($action, $hydrant_uuid){
	
}

function hydrantInspectionEntry($action, $inspection_uuid){
	
}

function fileEntry($action, $file_uuid){
	
}




function logbookEnry($action_id, $object_uuid){
	
	if($action_id < 20){
		userEntry($action_id, $object_uuid);
		
	} else if ($action_id < 20){
		loginEntry($action_id, $object_uuid);
		
	} else if ($action_id < 30){
		mailEntry($action_id, $object_uuid);
		
	} else if ($action_id < 110){
		eventEntry($action_id, $object_uuid);
		
	} else if ($action_id < 130){
		eventStaffEntry($action_id, $object_uuid);
		
	} else if ($action_id < 140){
		staffTemplateEntry($action_id, $object_uuid);
		
	} else if ($action_id < 170){
		eventReportEntry($action_id, $object_uuid);
		
	} else if ($action_id < 210){
		hydrantEntry($action_id, $object_uuid);
		
	} else if ($action_id < 220){
		hydrantInspectionEntry($action_id, $object_uuid);
		
	} else if ($action_id < 310){
		fileEntry($action_id, $object_uuid);
	}
}
?>
