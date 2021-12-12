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
	protected ?string $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $type;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected bool $isSeries;
	
	
	/**
	 * @return mixed
	 */
	public function getUuid() : ?string {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getType() : ?string{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getIsSeries() : int{
		return $this->isSeries;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $type
	 */
	public function setType(?string $type) {
		$this->type = $type;
	}

	/**
	 * @param string $isSeries
	 */
	public function setIsSeries(bool $isSeries) {
		$this->isSeries = $isSeries;
	}
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct(?string $uuid, ?string $type, bool $isSeries = false) {
		parent::__construct();
		$this->uuid = $uuid;
		$this->type = $type;
		$this->isSeries = $isSeries;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'type' => $this->type,
				'isSeries' => $this->isSeries,
		];
	}
}