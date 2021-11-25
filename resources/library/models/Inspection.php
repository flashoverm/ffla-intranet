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
	protected ?string $uuid;
	
	protected $date;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $vehicle;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $name;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $notes;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Engine")
	 * @ORM\JoinColumn(name="engine", referencedColumnName="uuid")
	 */
	protected ?Engine $engine;
	
	protected array $inspectedHydrants;
		
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
	public function getVehicle() : ?string {
		return $this->vehicle;
	}

	/**
	 * @return mixed
	 */
	public function getName() : ?string {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getNotes() : ?string {
		return $this->notes;
	}

	/**
	 * @return mixed
	 */
	public function getEngine() : ?Engine {
		return $this->engine;
	}

	/**
	 * @return multitype:
	 */
	public function getInspectedHydrants() : array {
		return $this->inspectedHydrants;
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
	 * @param mixed $vehicle
	 */
	public function setVehicle(?string $vehicle) {
		$this->vehicle = $vehicle;
	}

	/**
	 * @param mixed $name
	 */
	public function setName(?string $name) {
		$this->name = $name;
	}

	/**
	 * @param mixed $notes
	 */
	public function setNotes(?string $notes) {
		$this->notes = $notes;
	}

	/**
	 * @param mixed $engine
	 */
	public function setEngine(?Engine $engine) {
		$this->engine = $engine;
	}

	/**
	 * @param multitype: $inspectedHydrant
	 */
	public function setInspectedHydrants(array $inspectedHydrants) {
		$this->inspectedHydrants = $inspectedHydrants;
	}
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct(){
		parent::__construct();
		$this->uuid = NULL;
		$this->date = NULL;
		$this->engine = NULL;
		$this->name = NULL;
		$this->notes = NULL;
		$this->vehicle = NULL;
		$this->inspectedHydrants = array();
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'date' => $this->date,
				'engine' => $this->engine,
				'name' => $this->name,
				'notes' => $this->notes,
				'vehicle' => $this->vehicle,
				'inspectedHydrants' => $this->inspectedHydrants,
		];
	}
	
	public function addInspectedHydrant(InspectedHydrant $inspectedHydrant){
		$this->inspectedHydrants[] = $inspectedHydrant;
	}
	
	public function clearInspectedHydrants(){
		$this->inspectedHydrants = array();
	}
	
	public function getMaxIdx() : int {
		return end($this->inspectedHydrants)->getIndex();
	}
	
	public function getCount() : int{
		return count($this->inspectedHydrants);
	}
	
	public function setInspectionData($date, ?string $name, ?string $vehicle, ?string $notes){
		$this->setDate($date);
		$this->setName($name);
		$this->setVehicle($vehicle);
		$this->setNotes($notes);
	}
}