<?php 
require_once (realpath ( dirname ( __FILE__ ) . "/../../config.php" ));
require_once LIBRARY_PATH . "/db_inspection.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/db_report.php";
require_once LIBRARY_PATH . "/db_staffpositions.php";
require_once LIBRARY_PATH . "/db_files.php";

class LogbookEntry {
	
	public $uuid;
	public $timestamp;
	public $actionId;
	public $user;
	public $objects;
	public $message;
	
	protected function __construct() {
		
	}
	
	public static function fromAction($actionId, $objects){
		$instance = new LogbookEntry();
		
		$instance->uuid = getGUID ();
		$instance->timestamp = date('Y-m-d H:i:s');
		$instance->actionId = $actionId;
		$instance->user = NULL;
		if(isset($_SESSION ['intranet_userid'])){
			$instance->user = $_SESSION ['intranet_userid'];
		}
		$instance->objects = $objects;
		$instance->message = LogbookEntry::logbookEnry($actionId, $objects);
		return $instance;
	}
	
	public static function fromDB($result){
		$instance = new LogbookEntry();
		
		$instance->uuid = $result->uuid;
		$instance->timestamp = $result->timestamp;
		$instance->actionId = $result->action;
		$instance->objects = $result->object;
		$instance->message = $result->message;
		return $instance;
	}
		
	/*
	 * Log message create functions
	 */
	
	protected static function userEntry($action, $user_uuid){
		global $logbookActions;
		$user = get_user($user_uuid);
		if( ! $user ){
			return null;
		}
		return $logbookActions[$action] . ": " . $user->firstname . " " . $user->lastname . " (" . $user->email . ")";
	}
	
	protected static function loginEntry($action, $user_uuid){
		global $logbookActions;
		$user = get_user($user_uuid);
		if( ! $user ){
			return null;
		}
		return $logbookActions[$action] . ": " . $user->firstname . " " . $user->lastname . " (" . $user->email . ")";
	}
	
	protected static function logbookEntry($action, $event_uuid){
		global $logbookActions;
		return $logbookActions[$action];
	}
	
	protected static function eventEntry($action, $event_uuid){
		global $logbookActions, $config;
		$event = get_event($event_uuid);
		if( ! $event ){
			return null;
		}
		return $logbookActions[$action] . ":<br>"
				. "Wache: " . get_eventtype($event->type)->type . " (" . date($config ["formats"] ["date"], strtotime($event->date)) . " " . date($config ["formats"] ["time"], strtotime($event->start_time)) . " Uhr)<br>"
						. "Titel: " . $event->title ;
	}
	
	protected static function eventStaffEntry($action, $staff_uuid){
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
	
	protected static function staffTemplateEntry($action, $eventtype_uuid){
		global $logbookActions;
		if( ! true ){
			return null;
		}
		return $logbookActions[$action];
	}
	
	protected static function eventReportEntry($action, $report_uuid){
		global $logbookActions, $config;;
		$report = get_report($report_uuid);
		if( ! $report ){
			return null;
		}
		return $logbookActions[$action] . ":<br>"
				. "Bericht für: " . get_eventtype($report->type)->type . " (" . date($config ["formats"] ["date"], strtotime($report->date)) . " " . date($config ["formats"] ["time"], strtotime($report->start_time)) . " Uhr)<br>"
						. "Titel: " . $report->title ;
	}
	
	protected static function eventReportExport($action){
		global $logbookActions;
		return $logbookActions[$action];
	}
	
	protected static function hydrantEntry($action, $hydrant_uuid){
		global $logbookActions;
		$hydrant = get_hydrant_by_uuid($hydrant_uuid);
		if( ! $hydrant ){
			return null;
		}
		return $logbookActions[$action] . ": HY-Nr. " . $hydrant->hy;
	}
	
	protected static function hydrantInspectionEntry($action, $inspection_uuid){
		global $logbookActions, $config;;
		$inspection = get_inspection($inspection_uuid);
		if( ! $inspection ){
			return null;
		}
		return $logbookActions[$action] . ": " . $inspection->vehicle . " (" . date($config ["formats"] ["date"], strtotime($inspection->date)) . ")";
	}
	
	protected static function fileEntry($action, $file_uuid){
		global $logbookActions;
		$file = get_file($file_uuid);
		if( ! $file ){
			return null;
		}
		return $logbookActions[$action] . ": " . $file->description;
	}
	
	
	
	public static function logbookEnry($action_id, $objects){
		global $logbookActions;
		$message = null;
		
		if($action_id < 20){
			$message = LogbookEntry::userEntry($action_id, $objects);
			
		} else if ($action_id < 30){
			$message = LogbookEntry::loginEntry($action_id, $objects);

		} else if ($action_id < 100){
			$message = LogbookEntry::logbookEntry($action_id, $objects);
			
		} else if ($action_id < 110){
			$message = LogbookEntry::eventEntry($action_id, $objects);
			
		} else if ($action_id < 130){
			$message = LogbookEntry::eventStaffEntry($action_id, $objects);
			
		} else if ($action_id < 140){
			$message = LogbookEntry::staffTemplateEntry($action_id, $objects);
			
		} else if ($action_id < 190){
			$message = LogbookEntry::eventReportEntry($action_id, $objects);
			
		} else if ($action_id < 200){
			$message = LogbookEntry::eventReportExport($action_id);
			
		} else if ($action_id < 210){
			$message = LogbookEntry::hydrantEntry($action_id, $objects);
			
		} else if ($action_id < 220){
			$message = LogbookEntry::hydrantInspectionEntry($action_id, $objects);
			
		} else if ($action_id < 310){
			$message = LogbookEntry::fileEntry($action_id, $objects);
		}
		
		if($message == null){
			$message = "Log-Nachricht für '" . $logbookActions[$action_id] . "' konnte nicht erzeugt werden";
		}
		return $message;
	}
}

?>