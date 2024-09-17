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
	const ENGINECONFIRMATIONMANAGER = "ENGINECONFIRMATIONMANAGER";
	
	const ENGINEHYDRANTMANANGER = "ENGINEHYDRANTMANANGER";
	const HYDRANTADMINISTRATOR = "HYDRANTADMINISTRATOR";
	
	const ENGINEMANAGER = "ENGINEMANAGER";
	const PORTALADMIN = "PORTALADMIN";
	const EDITUSER = "EDITUSER";
	
	const EVENTPARTICIPENT = "EVENTPARTICIPENT";
	const EVENTMANAGER = "EVENTMANAGER";
	const EVENTADMIN = "EVENTADMIN";
	

	/*
	 * Named uuid because priviledge used to be a database object
	 */
	protected ?string $uuid;
	
	protected ?string $description;
		
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
		return $this->getUuid();
	}
	
	public function getDescription() : ?string {
	    return $this->description;
	}
	
	/**
	 * @return mixed
	 */
	public function getIsDefault() : int{
		return $this->isDefault;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}
	
	public function setDescription(?string $description) {
	    $this->description = $description;
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
	
	public function __construct(?string $uuid, ?string $description, bool $isDefault) {
		parent::__construct();
		$this->uuid = $uuid;
		$this->description = $description;
		$this->isDefault = $isDefault;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
		        'description' => $this->description,
				'isDefault' => $this->isDefault,
		];
	}
}
