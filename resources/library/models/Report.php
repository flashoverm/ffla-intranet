<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="report")
 */
class Report extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;

	protected $date;
	
	protected $startTime;
	
	protected $endTime;
	
	protected ?EventType $type;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $typeOther;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $title;
	
	protected ?Engine $engine;
	
	protected ?User $creator;
	
	protected $createDate;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected bool $noIncidents;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected bool $ilsEntry;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected bool $emsEntry;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected bool $managerApproved;
	
	
	protected $managerApprovedDate;
	
	
	protected ?User $managerApprovedBy;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $reportText;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $eventUuid;
	
	
	protected array $units;
	
	
	/**
	 * @return NULL
	 */
	public function getCreateDate() {
		return $this->createDate;
	}

	/**
	 * @return NULL
	 */
	public function getManagerApprovedDate() {
		return $this->managerApprovedDate;
	}

	/**
	 * @return NULL
	 */
	public function getManagerApprovedBy() : ?User {
		return $this->managerApprovedBy;
	}

	/**
	 * @param NULL $createDate
	 */
	public function setCreateDate($createDate) {
		$this->createDate = $createDate;
	}

	/**
	 * @param NULL $managerApprovedDate
	 */
	public function setManagerApprovedDate($managerApprovedDate) {
		$this->managerApprovedDate = $managerApprovedDate;
	}

	/**
	 * @param NULL $managerApprovedBy
	 */
	public function setManagerApprovedBy(?User $managerApprovedBy) {
		$this->managerApprovedBy = $managerApprovedBy;
	}

	/**
	 * @return mixed
	 */
	public function getUuid() : ?string {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return mixed
	 */
	public function getStartTime() {
		return $this->startTime;
	}

	/**
	 * @return mixed
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * @return mixed
	 */
	public function getType() : ?EventType {
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getTypeOther() : ?string {
		return $this->typeOther;
	}

	/**
	 * @return mixed
	 */
	public function getTitle() : ?string {
		return $this->title;
	}

	/**
	 * @return mixed
	 */
	public function getEngine() : ?Engine {
		return $this->engine;
	}

	/**
	 * @return mixed
	 */
	public function getCreator() : ?User {
		return $this->creator;
	}

	/**
	 * @return mixed
	 */
	public function getNoIncidents() : int {
		return $this->noIncidents;
	}

	/**
	 * @return mixed
	 */
	public function getIlsEntry() : int {
		return $this->ilsEntry;
	}

	/**
	 * @return mixed
	 */
	public function getEmsEntry() : int {
		return $this->emsEntry;
	}

	/**
	 * @return mixed
	 */
	public function getManagerApproved() : int {
		return $this->managerApproved;
	}

	/**
	 * @return mixed
	 */
	public function getReportText() : ?string {
		return $this->reportText;
	}

	/**
	 * @return mixed
	 */
	public function getEventUuid() : ?string {
		return $this->eventUuid;
	}

	/**
	 * @return mixed
	 */
	public function getUnits() : array {
		return $this->units;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @param mixed $startTime
	 */
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}

	/**
	 * @param mixed $endTime
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * @param mixed $type
	 */
	public function setType(?EventType $type) {
		$this->type = $type;
	}

	/**
	 * @param mixed $typeOther
	 */
	public function setTypeOther(?string $typeOther) {
		$this->typeOther = $typeOther;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle(?string $title) {
		$this->title = $title;
	}

	/**
	 * @param mixed $engine
	 */
	public function setEngine(?Engine $engine) {
		$this->engine = $engine;
	}

	/**
	 * @param mixed $creator
	 */
	public function setCreator(?User $creator) {
		$this->creator = $creator;
	}

	/**
	 * @param mixed $noIncidents
	 */
	public function setNoIncidents(bool $noIncidents) {
		$this->noIncidents = $noIncidents;
	}

	/**
	 * @param mixed $ilsEntry
	 */
	public function setIlsEntry(bool $ilsEntry) {
		$this->ilsEntry = $ilsEntry;
	}

	/**
	 * @param mixed $emsEntry
	 */
	public function setEmsEntry(bool $emsEntry) {
		$this->emsEntry = $emsEntry;
	}

	/**
	 * @param mixed $managerApproved
	 */
	public function setManagerApproved(bool $managerApproved) {
		$this->managerApproved = $managerApproved;
	}

	/**
	 * @param mixed $reportText
	 */
	public function setReportText(?string $reportText) {
		$this->reportText = $reportText;
	}

	/**
	 * @param mixed $eventUuid
	 */
	public function setEventUuid(?string $eventUuid) {
		$this->eventUuid = $eventUuid;
	}

	/**
	 * @param mixed $units
	 */
	public function setUnits(array $units) {
		$this->units = $units;
	}
	
	

	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->creator = NULL;
		$this->createDate = NULL;
		$this->date = NULL;
		$this->emsEntry = false;
		$this->endTime = NULL;
		$this->engine = NULL;
		$this->eventUuid = NULL;
		$this->ilsEntry = false;
		$this->managerApproved = false;
		$this->managerApprovedBy = NULL;
		$this->managerApprovedDate = NULL;
		$this->noIncidents = false;
		$this->reportText = NULL;
		$this->startTime = NULL;
		$this->title = NULL;
		$this->type = NULL;
		$this->typeOther = NULL;
		$this->units = array();
		$this->uuid = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'creator' => $this->creator,
				'createDate' => $this->createDate,
				'date' => $this->date,
				'emsEntry' => $this->emsEntry,
				'endTime' => $this->endTime,
				'engine' => $this->engine,
				'eventUuid' => $this->eventUuid,
				'ilsEntry' => $this->ilsEntry,
				'managerApproved' => $this->managerApproved,
				'managerApprovedDate' => $this->managerApprovedDate,
				'managerApprovedBy' => $this->managerApprovedBy,
				'noIncidents' => $this->noIncidents,
				'reportText' => $this->reportText,
				'startTime' => $this->startTime,
				'title' => $this->title,
				'type' => $this->type,
				'typeOther' => $this->typeOther,
				'units' => $this->units,
		];
	}
	
	function addReportUnit(ReportUnit $reportUnit){
		$this->units[] = $reportUnit;
	}
	
	function clearReportUnits(){
		$this->units = array();
	}
	
	function setReportData($date, $start_time, $end_time, EventType $type, ?string $type_other,
			?string $title, Engine $engine, bool $noIncidents, ?string $report, 
			?User $creator, bool $ilsEntry) {
		
		$this->setDate($date);
		$this->setStartTime($start_time);
		$this->setEndTime($end_time);
		$this->setType($type);
		$this->setTypeOther($type_other);
		$this->setTitle($title);
		$this->setEngine($engine);
		$this->setNoIncidents($noIncidents);
		$this->setReportText($report);
		$this->setCreator($creator);
		$this->setIlsEntry($ilsEntry);
	}
	
	function setDataOfEvent(Event $event){
		$this->setDate($event->getDate());
		$this->setStartTime($event->getStartTime());
		$this->setEndTime($event->getEndTime());
		$this->setType($event->getType());
		$this->setTypeOther($event->getTypeOther());
		$this->setEngine($event->getEngine());
		$this->setTitle($event->getTitle());
		$this->setEventUuid($event->getUuid());
		
		$reportUnit = new ReportUnit("Stationäre Wache", $event->getDate(), $event->getStartTime(), $event->getEndTime());
		
		foreach($event->getStaff() as $eventStaff){
			if($eventStaff->getUser() != NULL){
				$reportStaff = new ReportStaff(
						$eventStaff->getPosition(),
						$eventStaff->getUser());
				$reportUnit->addStaff($reportStaff);
			}
		}
		$this->addReportUnit($reportUnit);
	}
	
	function hasOldStaffFormat() : bool {
	    foreach ( $this->getUnits() as $unit ) {
	        foreach ( $unit->getStaff() as $staff ) {
	            if($staff->getName() != null){
	                return true;
	            }
	        }
	    }
	    return false;
	}
		
	
}