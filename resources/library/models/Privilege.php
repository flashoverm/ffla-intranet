<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="privilege")
 */
class Privilege extends BaseModel {
	
	const FILEADMIN = "FILEADMIN";
	
	const FFADMINISTRATION = "FFADMINISTRATION";
	
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
	protected $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $privilege;
	
	/**
	 * @ORM\Column(name="is_default", type="boolean")
	 */
	protected $isDefault;
	
	/**
	 * @ORM\ManyToMany(targetEntity="User", mappedBy="privileges")
	 */
	/*
	protected $users;
	*/
	
	
	function __construct($uuid, $privilege, $isDefault) {
		$this->uuid = $uuid;
		$this->privilege = $privilege;
		$this->isDefault = $isDefault;
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
	public function getPrivilege() {
		return strtoupper($this->privilege);
	}

	/**
	 * @return mixed
	 */
	public function getIsDefault() {
		return $this->isDefault;
	}

	/**
	 * @return mixed
	 */
	/*
	public function getUsers() {
		return $this->users;
	}
	*/

	/**
	 * @param mixed $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $privilege
	 */
	public function setPrivilege($privilege) {
		$this->privilege = strtoupper($privilege);
	}

	/**
	 * @param mixed $isDefault
	 */
	public function setIsDefault($isDefault) {
		$this->isDefault = $isDefault;
	}

	/**
	 * @param mixed $users
	 */
	/*
	public function setUsers($users) {
		$this->users = $users;
	}
	*/

}