<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="inspection")
 */
class Inspection extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected $uuid;
	
	protected $date;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $vehicle;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $name;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $notes;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Engine")
	 * @ORM\JoinColumn(name="engine", referencedColumnName="uuid")
	 */
	protected $engine;
	
	protected $inspectedHydrants;
	
	
	
	function __construct(){
		$this->inspectedHydrants = array();
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
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return mixed
	 */
	public function getVehicle() {
		return $this->vehicle;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getNotes() {
		return $this->notes;
	}

	/**
	 * @return mixed
	 */
	public function getEngine() {
		return $this->engine;
	}

	/**
	 * @return multitype:
	 */
	public function getInspectedHydrants() {
		return $this->inspectedHydrants;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @param mixed $vehicle
	 */
	public function setVehicle($vehicle) {
		$this->vehicle = $vehicle;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param mixed $notes
	 */
	public function setNotes($notes) {
		$this->notes = $notes;
	}

	/**
	 * @param mixed $engine
	 */
	public function setEngine($engine) {
		$this->engine = $engine;
	}

	/**
	 * @param multitype: $inspectedHydrant
	 */
	public function setInspectedHydrant($inspectedHydrants) {
		$this->inspectedHydrants = $inspectedHydrants;
	}
	
	
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function addInspectedHydrant(InspectedHydrant $inspectedHydrant){
		$this->inspectedHydrants[] = $inspectedHydrant;
	}
	
	public function clearInspectedHydrants(){
		$this->inspectedHydrants = array();
	}
	
	public function getMaxIdx(){
		return end($this->inspectedHydrants)->getIndex();
	}
	
	public function getCount(){
		return count($this->inspectedHydrants);
	}
	
	public function setInspectionData($date, $name, $vehicle, $notes){
		$this->setDate($date);
		$this->setName($name);
		$this->setVehicle($vehicle);
		$this->setNotes($notes);
	}
}