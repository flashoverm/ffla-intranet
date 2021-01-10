<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="event")
 */
class Event extends BaseModel {
	
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
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $comment;
	
	protected ?Engine $engine;
	
	protected ?User $creator;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected bool $published;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected bool $staffConfirmation;
	
	protected ?User $deletedBy;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $hash;
	
	protected array $staff;
	

	/**
	 * @return NULL
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
	 * @return NULL
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
	public function getComment() : ?string {
		return $this->comment;
	}

	/**
	 * @return NULL
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
	public function getPublished() : bool {
		return $this->published;
	}

	/**
	 * @return mixed
	 */
	public function getStaffConfirmation() : bool {
		return $this->staffConfirmation;
	}

	/**
	 * @return mixed
	 */
	public function getDeletedBy() : ?User {
		return $this->deletedBy;
	}

	/**
	 * @return mixed
	 */
	public function getHash() : ?string {
		return $this->hash;
	}
	
	/**
	 * @return mixed
	 */
	public function getStaff() : array {
		return $this->staff;
	}

	/**
	 * @param NULL $uuid
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
	 * @param NULL $type
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
	 * @param mixed $comment
	 */
	public function setComment(?string $comment) {
		$this->comment = $comment;
	}

	/**
	 * @param NULL $engine
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
	 * @param mixed $published
	 */
	public function setPublished(bool $published) {
		$this->published = $published;
	}

	/**
	 * @param mixed $staffConfirmation
	 */
	public function setStaffConfirmation(bool $staffConfirmation) {
		$this->staffConfirmation = $staffConfirmation;
	}

	/**
	 * @param mixed $deletedBy
	 */
	public function setDeletedBy(?User $deletedBy) {
		$this->deletedBy = $deletedBy;
	}

	/**
	 * @param mixed $hash
	 */
	public function setHash(?string $hash) {
		$this->hash = $hash;
	}
	
	/**
	 * @param mixed $staff
	 */
	public function setStaff(array $staff) {
		$this->staff = $staff;
	}
	
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->comment = NULL;
		$this->creator = NULL;
		$this->date = NULL;
		$this->deletedBy = NULL;
		$this->endTime = NULL;
		$this->engine = NULL;
		$this->hash = NULL;
		$this->published = false;
		$this->staff = array();
		$this->staffConfirmation = false;
		$this->startTime = NULL;
		$this->title = NULL;
		$this->type = NULL;
		$this->typeOther = NULL;
		$this->uuid = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	function setEventData($date, $startTime, $endTime, $type, $typeOther, $title, $comment, $engine, $staffConfirmation){
		$this->setDate($date);
		$this->setStartTime($startTime);
		$this->setEndTime($endTime);
		$this->setType($type);
		$this->setTypeOther($typeOther);
		$this->setTitle($title);
		$this->setComment($comment);
		$this->setEngine($engine);
		$this->setStaffConfirmation($staffConfirmation);
	}
	
	function getOccupancy(){
		$occupancy = 0;
		foreach ($this->staff as $staff){
			if($staff->getUser() != NULL){
				$occupancy ++;
			}
		}
		return $occupancy . "/" . sizeof($this->staff);
	}
	
	function isEventFull(){
		foreach ($this->staff as $staff){
			if($staff->getUser() == NULL){
				return false;
			}
		}
		return true;
	}
	
	function isUserAlreadyStaff($userUuid){
		foreach ($this->staff as $staff){

			if($staff->getUser() != NULL && $staff->getUser()->getUuid() == $userUuid){
				return true;
			}
		}
		return false;
	}
	
}