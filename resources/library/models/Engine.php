<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="engine")
 */
class Engine extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $name;
	
	/**
	 * @ORM\Column(name="isadministration", type="boolean")
	 */
	protected bool $isAdministration;
	
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
	public function getIsAdministration() : bool {
		return $this->isAdministration;
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
	 * @param mixed $isAdministration
	 */
	public function setIsAdministration(bool $isAdministration) {
		$this->isAdministration = $isAdministration;
	}

	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct(?string $uuid, ?string $name, bool $isadministration) {
		parent::__construct();
		$this->uuid = $uuid;
		$this->name = $name;
		$this->isAdministration = $isadministration;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
}