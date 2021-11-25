<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="event")
 */
class Staff extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
	protected ?StaffPosition $position;
	
	protected ?User $user;
	
	protected bool $unconfirmed;
	
	protected bool $userAcknowledged;
	
	protected ?string $eventUuid;
	

	/**
	 * @return NULL
	 */
	public function getUuid() : ?string {
		return $this->uuid;
	}

	/**
	 * @return NULL
	 */
	public function getPosition() : ?StaffPosition {
		return $this->position;
	}

	/**
	 * @return NULL
	 */
	public function getUser() : ?User {
		return $this->user;
	}

	/**
	 * @return boolean
	 */
	public function getUnconfirmed() : bool {
		return $this->unconfirmed;
	}
	
	/**
	 * @return boolean
	 */
	public function getUserAcknowledged() : bool {
		return $this->userAcknowledged;
	}
	
	/**
	 * @return NULL
	 */
	public function getEventUuid() : ?string {
		return $this->eventUuid;
	}

	/**
	 * @param NULL $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param NULL $position
	 */
	public function setPosition(?StaffPosition $position) {
		$this->position = $position;
	}

	/**
	 * @param NULL $user
	 */
	public function setUser(?User $user) {
		$this->user = $user;
	}

	/**
	 * @param boolean $unconfirmed
	 */
	public function setUnconfirmed(bool $unconfirmed) {
		$this->unconfirmed = $unconfirmed;
	}
	
	/**
	 * @param boolean $userAcknowledged
	 */
	public function setUserAcknowledged(bool $userAcknowledged) {
		$this->userAcknowledged = $userAcknowledged;
	}
	
	/**
	 * @param NULL $uuid
	 */
	public function setEventUuid(?string $eventUuid) {
		$this->eventUuid = $eventUuid;
	}
	
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->uuid = NULL;
		$this->position = NULL;
		$this->user = NULL;
		$this->unconfirmed = false;
		$this->eventUuid = NULL;
		$this->userAcknowledged = false;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'position' => $this->position,
				'user' => $this->user,
				'unconfirmed' => $this->unconfirmed,
				'eventUuid' => $this->eventUuid,
				'userAcknowledged' => $this->userAcknowledged,
		];
	}
}