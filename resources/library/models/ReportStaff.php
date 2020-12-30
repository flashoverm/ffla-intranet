<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="report_staff")
 */
class ReportStaff extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $name;
	
	protected ?Engine $engine;
	
	protected ?StaffPosition $position;
	
	protected ?string $unitUuid;
	
	/**
	 * @return mixed
	 */
	public function getUuid() : ?string {
		return $this->uuid;
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
	public function getEngine() : ?Engine {
		return $this->engine;
	}

	/**
	 * @return mixed
	 */
	public function getPosition() : ?StaffPosition {
		return $this->position;
	}
	
	/**
	 * @return mixed
	 */
	public function getUnitUuid() : ?string {
		return $this->unitUuid;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $name
	 */
	public function setName(?string $name) {
		$this->name = $name;
	}

	/**
	 * @param mixed $engine
	 */
	public function setEngine(?Engine $engine) {
		$this->engine = $engine;
	}

	/**
	 * @param mixed $position
	 */
	public function setPosition(?StaffPosition $position) {
		$this->position = $position;
	}
	
	/**
	 * @param mixed $name
	 */
	public function setUnitUuid(?string $unitUuid) {
		$this->unitUuid = $unitUuid;
	}
	
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct(StaffPosition $position, string $name, Engine $engine) {
		parent::__construct();
		$this->engine = $engine;
		$this->name = $name;
		$this->position = $position;
		$this->uuid = NULL;
		$this->unitUuid = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */

	
}