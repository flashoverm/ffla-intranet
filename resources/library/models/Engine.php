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
	protected $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $name;
	
	/**
	 * @ORM\Column(name="isadministration", type="boolean")
	 */
	protected $isAdministration;
	
	
	function __construct($uuid, $name, $isadministration) {
		$this->uuid = $uuid;
		$this->name = $name;
		$this->isAdministration = $isadministration;
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
	public function getName() {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getIsAdministration() {
		return $this->isAdministration;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param mixed $isAdministration
	 */
	public function setIsAdministration($isAdministration) {
		$this->isAdministration = $isAdministration;
	}

	
	
}