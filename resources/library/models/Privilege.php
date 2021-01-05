<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="privilege")
 */
class Privilege extends BaseModel {
	
	const FILEADMIN = "FILEADMIN";
	
	const FFADMINISTRATION = "FFADMINISTRATION";
	const MASTERDATAADMIN = "MASTERDATAADMIN";
	
	const ENGINEHYDRANTMANANGER = "ENGINEHYDRANTMANANGER";
	const HYDRANTADMINISTRATOR = "HYDRANTADMINISTRATOR";
	
	const PORTALADMIN = "PORTALADMIN";
	const EDITUSER = "EDITUSER";
	
	const EVENTPARTICIPENT = "EVENTPARTICIPENT";
	const EVENTMANAGER = "EVENTMANAGER";
	const EVENTADMIN = "EVENTADMIN";
	

	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $privilege;
	
	/**
	 * @ORM\Column(name="is_default", type="boolean")
	 */
	protected bool $isDefault;
	
	
	/**
	 * @return mixed
	 */
	public function getUuid() : ?string{
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getPrivilege() : ?string {
		return strtoupper($this->privilege);
	}

	/**
	 * @return mixed
	 */
	public function getIsDefault() : bool{
		return $this->isDefault;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $privilege
	 */
	public function setPrivilege(?string $privilege) {
		$this->privilege = strtoupper($privilege);
	}

	/**
	 * @param mixed $isDefault
	 */
	public function setIsDefault(bool $isDefault) {
		$this->isDefault = $isDefault;
	}

	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct(?string $uuid, ?string $privilege, bool $isDefault) {
		parent::__construct();
		$this->uuid = $uuid;
		$this->privilege = $privilege;
		$this->isDefault = $isDefault;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
}