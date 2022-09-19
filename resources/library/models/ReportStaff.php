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
	protected ?User $user;
		
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
	public function getUser() : ?User {
		return $this->user;
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
	public function setUser(?User $user) {
		$this->user = $user;
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
	
	function __construct(StaffPosition $position, User $user) {
		parent::__construct();
		$this->user = $user;
		$this->position = $position;
		$this->uuid = NULL;
		$this->unitUuid = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */

	public function jsonSerialize() {
		return [
				'user' => $this->user,
				'position' => $this->position,
				'uuid' => $this->uuid,
				'unitUuid' => $this->unitUuid,
		];
	}
}