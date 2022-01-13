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
	protected bool $sendNoReport;
	
	
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
	public function getSendNoReport() : int{
		return $this->sendNoReport;
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
	 * @param string $sendNoReport
	 */
	public function setSendNoReport(bool $sendNoReport) {
		$this->sendNoReport = $sendNoReport;
	}
	
	
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct(?string $uuid, ?string $type, bool $sendNoReport = false) {
		parent::__construct();
		$this->uuid = $uuid;
		$this->type = $type;
		$this->sendNoReport = $sendNoReport;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'type' => $this->type,
				'sendNoReport' => $this->sendNoReport,
		];
	}
}