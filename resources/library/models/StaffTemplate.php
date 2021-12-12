<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="stafftemplate")
 */
class StaffTemplate extends BaseModel {
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?EventType $eventType;
	

	protected array $staffPositions;

	
	/**
	 * @return mixed
	 */
	public function getEventType() : ?EventType {
		return $this->eventType;
	}

	/**
	 * @return mixed
	 */
	public function getStaffPositions() : array {
		return $this->staffPositions;
	}

	/**
	 * @param mixed $eventType
	 */
	public function setEventType(EventType $eventType) {
		$this->eventType = $eventType;
	}

	/**
	 * @param mixed $staffPositions
	 */
	public function setStaffPositions(array $staffPositions) {
		$this->staffPositions = $staffPositions;
	}
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->eventType = NULL;
		$this->staffPositions = array();
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'eventType' => $this->eventType,
				'staffPositions' => $this->staffPositions,
		];
	}
	
	public function addStaffposition(StaffPosition $staffPosition){
		$this->staffPositions[] = $staffPosition;
	}
	
	public function clearStaffpositions(){
		$this->staffPositions = array();
	}
	
}