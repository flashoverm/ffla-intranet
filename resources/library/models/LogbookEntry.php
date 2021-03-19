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
	protected ?string $uuid;
	
	
	protected $timestamp;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected int $action;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $user;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $object;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $message;
	

	/**
	 * @return mixed
	 */
	public function getUuid() : ?string {
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
	public function getAction() : int {
		return $this->action;
	}

	/**
	 * @return mixed
	 */
	public function getUser() : ?string {
		return $this->user;
	}

	/**
	 * @return mixed
	 */
	public function getObject() : ?string {
		return $this->object;
	}

	/**
	 * @return mixed
	 */
	public function getMessage() : ?string {
		return $this->message;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
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
	public function setAction(int $action) {
		$this->action = $action;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser(?string $user) {
		$this->user = $user;
	}

	/**
	 * @param mixed $object
	 */
	public function setObjects(?string $object) {
		$this->object = $object;
	}

	/**
	 * @param mixed $message
	 */
	public function setMessage(?string $message) {
		$this->message = $message;
	}
	
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->action = 0;
		$this->message = NULL;
		$this->object = NULL;
		$this->timestamp = NULL;
		$this->user = NULL;
		$this->uuid = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public static function fromAction(int $actionId, ?string $objects){
		$entry = new LogbookEntry();
		
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
		global $config, $eventDAO;
		$event = $eventDAO->getEvent($event_uuid);
		if( ! $event ){
			return null;
		}
		return LogbookActions::getActionText($action) . ":<br>"
				. "Wache: " . $event->getType()->getType() . " (" . date($config ["formats"] ["date"], strtotime($event->getDate())) . " " . date($config ["formats"] ["time"], strtotime($event->getStartTime())) . " Uhr)<br>"
						. "Titel: " . $event->getTitle() ;
	}
	
	protected static function eventStaffEntry($action, $staff_uuid){
		global $config, $staffDAO, $eventDAO;
		$staff = $staffDAO->getEventStaffEntry($staff_uuid);
		if( ! $staff ){
			return null;
		}
		$event = $eventDAO->getEvent($staff->getEventUuid());
		
		return LogbookActions::getActionText($action) . ":<br>"
				. "Wache:  " . $event->getType()->getType() . " (" . date($config ["formats"] ["date"], strtotime($event->getDate())) . " " . date($config ["formats"] ["time"], strtotime($event->getStartTime())) . " Uhr) " . $staff->getPosition()->getPosition() . "<br>"
						. "Person: " . $staff->getUser()->getFullNameWithEmail();
	}
	
	protected static function staffTemplateEntry($action, $eventtype_uuid){
		if( ! true ){
			return null;
		}
		return LogbookActions::getActionText($action);
	}
	
	protected static function eventReportEntry($action, $report_uuid){
		global $config, $reportDAO;
		$report = $reportDAO->getReport($report_uuid);
		if( ! $report ){
			return null;
		}
		return LogbookActions::getActionText($action) . ":<br>"
				. "Bericht für: " . $report->getType()->getType() . " (" . date($config ["formats"] ["date"], strtotime($report->getDate())) . " " . date($config ["formats"] ["time"], strtotime($report->getStartTime())) . " Uhr)<br>"
						. "Titel: " . $report->getTitle() ;
	}
	
	protected static function eventReportExport($action){
		return LogbookActions::getActionText($action);
	}
	
	protected static function hydrantEntry($action, $hydrant_uuid){
		global $hydrantDAO;
		$hydrant = $hydrantDAO->getHydrantByUuid($hydrant_uuid);
		if( ! $hydrant ){
			return null;
		}
		return LogbookActions::getActionText($action) . ": HY-Nr. " . $hydrant->hy;
	}
	
	protected static function hydrantInspectionEntry($action, $inspection_uuid){
		global $config, $inspectionDAO;
		$inspection = $inspectionDAO->getInspection($inspection_uuid);
		if( ! $inspection ){
			return null;
		}
		return LogbookActions::getActionText($action) . ": " . $inspection->getVehicle() . " (" . date($config ["formats"] ["date"], strtotime($inspection->getDate())) . ")";
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
	
	protected static function dataChangeRequestEntry($action, $dataChangeRequestUuid){
		global $dataChangeRequestDAO;
		$dataChangeRequest = $dataChangeRequestDAO->getDataChangeRequest($dataChangeRequestUuid);
		
		if( ! $dataChangeRequest ){
			return null;
		}
		return LogbookActions::getActionText($action) . ": <br>" 
				. DataChangeRequest::DATATYPE_TEXT[$dataChangeRequest->getDatatype()] . " - Neuer Wert: " . $dataChangeRequest->getNewValue()
				. "Antragsteller: " . $dataChangeRequest->getUser()->getFullNameWithEmail();
	}
	
	
	
	public static function logbookEnry(int $action_id, ?string $objects){
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
			
		} else if ($action_id < 510){
			$message = LogbookEntry::dataChangeRequestEntry($action_id, $objects);
		}
		
		if($message == null){
			$message = "Log-Nachricht für '" . LogbookActions::getActionText($action_id) . "' konnte nicht erzeugt werden";
		}
		return $message;
	}
}

?>