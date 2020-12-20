<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="logbook")
 */
class LogbookEntry extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected $uuid;
	
	
	protected $timestamp;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected $action;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $user;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $object;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $message;
	
	
	public function __construct() {
		
	}
	
	
	/**
	 * @return mixed
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @return mixed
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @return mixed
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return mixed
	 */
	public function getObject() {
		return $this->object;
	}

	/**
	 * @return mixed
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $timestamp
	 */
	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	/**
	 * @param mixed $action
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
	 * @param mixed $object
	 */
	public function setObjects($object) {
		$this->object = $object;
	}

	/**
	 * @param mixed $message
	 */
	public function setMessage($message) {
		$this->message = $message;
	}
	
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public static function fromAction($actionId, $objects){
		$entry = new LogbookEntry();
		
		$entry->setUuid(getGUID ());
		$entry->setTimestamp(date('Y-m-d H:i:s'));
		$entry->setAction($actionId);
		$entry->setUser(NULL);
		if(isset($_SESSION ['intranet_userid'])){
			$entry->setUser($_SESSION ['intranet_userid']);
		}
		$entry->setObjects($objects);
		$entry->setMessage(LogbookEntry::logbookEnry($actionId, $objects));
		
		return $entry;
	}
		
	/*
	 * Log message create functions
	 */
	
	protected static function userEntry($action, $user_uuid){
		global $userDAO;
		$user = $userDAO->getUserByUUID($user_uuid);
		if( ! $user ){
			return null;
		}
		return LogbookActions::getActionText($action) . ": " . $user->getFullNameWithEmail();
	}
	
	protected static function loginEntry($action, $user_uuid){
		global $userDAO;
		$user = $userDAO->getUserByUUID($user_uuid);
		if( ! $user ){
			return null;
		}
		return LogbookActions::getActionText($action) . ": " . $user->getFullNameWithEmail();
	}
	
	protected static function logbookEntry($action, $event_uuid){
		return LogbookActions::getActionText($action);
	}
	
	protected static function eventEntry($action, $event_uuid){
		global $config;
		$event = get_event($event_uuid);
		if( ! $event ){
			return null;
		}
		return LogbookActions::getActionText($action) . ":<br>"
				. "Wache: " . get_eventtype($event->type)->type . " (" . date($config ["formats"] ["date"], strtotime($event->date)) . " " . date($config ["formats"] ["time"], strtotime($event->start_time)) . " Uhr)<br>"
						. "Titel: " . $event->title ;
	}
	
	protected static function eventStaffEntry($action, $staff_uuid){
		global $config, $userDAO;
		$staff = get_events_staffposition($staff_uuid);
		if( ! $staff ){
			return null;
		}
		$staffpos = $staff->position;
		$event = get_event($staff->event);
		$user = $userDAO->getUserByUUID($staff->user);
		
		return LogbookActions::getActionText($action) . ":<br>"
				. "Wache:  " . get_eventtype($event->type)->type . " (" . date($config ["formats"] ["date"], strtotime($event->date)) . " " . date($config ["formats"] ["time"], strtotime($event->start_time)) . " Uhr) " . $staffpos . "<br>"
						. "Person: " . $user->getFullNameWithEmail();
	}
	
	protected static function staffTemplateEntry($action, $eventtype_uuid){
		if( ! true ){
			return null;
		}
		return LogbookActions::getActionText($action);
	}
	
	protected static function eventReportEntry($action, $report_uuid){
		global $config;;
		$report = get_report($report_uuid);
		if( ! $report ){
			return null;
		}
		return LogbookActions::getActionText($action) . ":<br>"
				. "Bericht für: " . get_eventtype($report->type)->type . " (" . date($config ["formats"] ["date"], strtotime($report->date)) . " " . date($config ["formats"] ["time"], strtotime($report->start_time)) . " Uhr)<br>"
						. "Titel: " . $report->title ;
	}
	
	protected static function eventReportExport($action){
		return LogbookActions::getActionText($action);
	}
	
	protected static function hydrantEntry($action, $hydrant_uuid){
		$hydrant = get_hydrant_by_uuid($hydrant_uuid);
		if( ! $hydrant ){
			return null;
		}
		return LogbookActions::getActionText($action) . ": HY-Nr. " . $hydrant->hy;
	}
	
	protected static function hydrantInspectionEntry($action, $inspection_uuid){
		global $config;;
		$inspection = get_inspection($inspection_uuid);
		if( ! $inspection ){
			return null;
		}
		return LogbookActions::getActionText($action) . ": " . $inspection->vehicle . " (" . date($config ["formats"] ["date"], strtotime($inspection->date)) . ")";
	}
	
	protected static function fileEntry($action, $file_uuid){
		global $fileDAO;
		$file = $fileDAO->getFile($file_uuid);
		if( ! $file ){
			return null;
		}
		return LogbookActions::getActionText($action) . ": " . $file->getDescription();
	}
	
	protected static function confirmationEntry($action, $confirmationUuid){
		global $config, $confirmationDAO;
		$confirmation = $confirmationDAO->getConfirmation($confirmationUuid);
		if( ! $confirmation ){
			return null;
		}		
		return LogbookActions::getActionText($action) . ": <br>" . $confirmation->getDescription() . " (" . date($config ["formats"] ["date"], strtotime($confirmation->getDate())) 
			. " " . date($config ["formats"] ["time"], strtotime($confirmation->getStartTime())) . " Uhr)<br>"
			. "Antragsteller: " . $confirmation->getUser()->getFullNameWithEmail();
	}
	
	
	
	public static function logbookEnry($action_id, $objects){
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
			
		} else if ($action_id < 410){
			$message = LogbookEntry::confirmationEntry($action_id, $objects);
		}
		
		if($message == null){
			$message = "Log-Nachricht für '" . LogbookActions::getActionText($action_id) . "' konnte nicht erzeugt werden";
		}
		return $message;
	}
}

?>