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
	protected ?string $uuid;
	
	protected $date;
	
	protected $startTime;
	
	protected $endTime;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $description;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected int $state;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $reason;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?User $user;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?User $lastAdvisor;
	
	protected $lastUpdate;
	
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
	public function getDescription() : ?string {
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function getState() : int{
		return $this->state;
	}

	/**
	 * @return mixed
	 */
	public function getReason() : ?string {
		return $this->reason;
	}

	/**
	 * @return mixed
	 */
	public function getUser() : ?User {
		return $this->user;
	}

	/**
	 * @return mixed
	 */
	public function getLastAdvisor() : ?User {
		return $this->lastAdvisor;
	}
	
	public function getLastUpdate() {
		return $this->lastUpdate;
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
	public function setDescription(?string $description) {
		$this->description = $description;
	}

	/**
	 * @param mixed $state
	 */
	public function setState(int $state) {
		$this->state = $state;
	}

	/**
	 * @param mixed $reason
	 */
	public function setReason(?string $reason) {
		$this->reason = $reason;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser(?User $user) {
		$this->user = $user;
	}

	/**
	 * @param mixed $lastAdvisor
	 */
	public function setLastAdvisor(?User $lastAdvisor) {
		$this->lastAdvisor = $lastAdvisor;
	}
	
	public function setLastUpdate($lastUpdate) {
		$this->lastUpdate = $lastUpdate;
	}

	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->uuid = NULL;
		$this->date = NULL;
		$this->startTime = NULL;
		$this->endTime = NULL;
		$this->description = NULL;
		$this->lastAdvisor = NULL;
		$this->reason = NULL;
		$this->state = 0;
		$this->user = NULL;
		$this->lastUpdate = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function setConfirmationData($date, $startTime, $endTime, ?string $description, ?User $user){
		$this->setDate($date);
		$this->setStartTime($startTime);
		$this->setEndTime($endTime);
		$this->setDescription($description);
		$this->setUser($user);
	}
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'date' => $this->date,
				'startTime' => $this->startTime,
				'endTime' => $this->endTime,
				'description' => $this->description,
				'lastAdvisor' => $this->lastAdvisor,
				'reason' => $this->reason,
				'state' => $this->state,
				'user' => $this->user,
				'lastUpdate' => $this->lastUpdate,
		];
	}
	
}