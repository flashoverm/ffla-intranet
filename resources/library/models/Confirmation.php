<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="confirmation")
 */
class Confirmation extends BaseModel {
	
	const OPEN = 1;
	const ACCEPTED = 2;
	const DECLINED = 3;
	
	const CONFIRMATION_STATES = array(
			
			1 => "Offen",
			2 => "Akzeptiert",
			3 => "Abgelehnt",
	);
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected $uuid;
	
	protected $date;
	
	protected $startTime;
	
	protected $endTime;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $description;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected $state;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $reason;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $user;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $lastAdvisor;

	
	
	public function __construct() {
		
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
	public function getStartTime() {
		return $this->startTime;
	}

	/**
	 * @return mixed
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @return mixed
	 */
	public function getReason() {
		return $this->reason;
	}

	/**
	 * @return mixed
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return mixed
	 */
	public function getLastAdvisor() {
		return $this->lastAdvisor;
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
	 * @param mixed $startTime
	 */
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}

	/**
	 * @param mixed $endTime
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param mixed $state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @param mixed $reason
	 */
	public function setReason($reason) {
		$this->reason = $reason;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
	 * @param mixed $lastAdvisor
	 */
	public function setLastAdvisor($lastAdvisor) {
		$this->lastAdvisor = $lastAdvisor;
	}

	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function setConfirmationData($date, $startTime, $endTime, $description, $user){
		$this->setDate($date);
		$this->setStartTime($startTime);
		$this->setEndTime($endTime);
		$this->setDescription($description);
		$this->setUser($user);
	}
	
}