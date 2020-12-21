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
	protected $eventType;
	

	protected $staffPositions;


	function __construct() {
		$this->staffPositions = array();
	}
	
	/**
	 * @return mixed
	 */
	public function getEventType() {
		return $this->eventType;
	}

	/**
	 * @return mixed
	 */
	public function getStaffPositions() {
		return $this->staffPositions;
	}

	/**
	 * @param mixed $eventType
	 */
	public function setEventType($eventType) {
		$this->eventType = $eventType;
	}

	/**
	 * @param mixed $staffPositions
	 */
	public function setStaffPositions($staffPositions) {
		$this->staffPositions = $staffPositions;
	}
	
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function addStaffposition(StaffPosition $staffPosition){
		$this->staffPositions[] = $staffPosition;
	}
	
	public function clearStaffpositions(){
		$this->staffPositions = array();
	}
	
	public function toJson(){
		$vars = get_object_vars($this);
		$vars['eventType'] = json_decode($this->eventType->toJson());
		$staffpositions = array();
		foreach($this->getStaffPositions() as $position){
			$staffpositions[] = json_decode($position->toJson());
		}
		$vars['staffPositions'] = $staffpositions; 
		return json_encode($vars, JSON_UNESCAPED_UNICODE);
	}
	
}