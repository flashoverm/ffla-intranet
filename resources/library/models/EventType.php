<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="eventtype")
 */
class EventType extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $type;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected $isSeries;
	
	
	function __construct($uuid, $type, $isSeries = false) {
		$this->uuid = $uuid;
		$this->type = $type;
		$this->isSeries = $isSeries;
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
	public function getType() {
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getIsSeries() {
		return $this->isSeries;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param mixed $isSeries
	 */
	public function setIsSeries($isSeries) {
		$this->isSeries = $isSeries;
	}

	
	
}