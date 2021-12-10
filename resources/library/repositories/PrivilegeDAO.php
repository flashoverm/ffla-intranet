<?php

require_once "BaseDAO.php";

class PrivilegeDAO extends BaseDAO{
	
	function __construct(PDO $pdo) {
		parent::__construct($pdo, "privilege");
	}

	function save(Privilege $privilege){
		$statement = $this->db->prepare("INSERT INTO privilege (uuid, privilege, is_default) VALUES (?, ?, ?)");
		
		$result = $statement->execute(array($privilege->getUuid(), $privilege->getPrivilege(), $privilege->getIsDefault()));
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	function getPrivileges(){
		$statement = $this->db->prepare("SELECT * FROM privilege ORDER BY privilege");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}

	function getPrivilege(String $uuid){
		$statement = $this->db->prepare("SELECT * FROM privilege WHERE uuid = ? ");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getPrivilegeByName(String $name){
		$statement = $this->db->prepare("SELECT * FROM privilege WHERE privilege = ? ");
		
		if ($statement->execute(array($name))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	
	/*
	 * Init and helper methods
	 */

	protected function resultToObject($result){
		$object = new Privilege($result['uuid'], $result['privilege'], $result['is_default']);
		return $object;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE privilege (
						  uuid CHAR(36) NOT NULL,
						  privilege VARCHAR(32) NOT NULL,
						  is_default BOOLEAN NOT NULL default 0,
                          PRIMARY KEY (uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			$this->initializePrivileges();
			return true;
		}
		return false;
	}
	
	protected function initializePrivileges(){
		$this->save(new Privilege('5873791F-68EC-159D-EF91-3288F02EF1D2', Privilege::FILEADMIN, false));
		$this->save(new Privilege('10590E6B-FC09-49B3-6A35-53759D10D1FC', Privilege::FFADMINISTRATION, false));
		$this->save(new Privilege('6B296269-6280-EAC5-B5F3-4A95C3FA7656', Privilege::ENGINEHYDRANTMANANGER, false));
		$this->save(new Privilege('2B3DE880-1EB7-C9A1-C533-BD90F773FDBA', Privilege::HYDRANTADMINISTRATOR, false));
		$this->save(new Privilege('EE50BFB0-B4B0-2AE2-AAE4-2FB6EE9DA558', Privilege::PORTALADMIN, false));
		$this->save(new Privilege('231C64FA-24F4-CDA4-60FE-B211A364D5AE', Privilege::EDITUSER, true));
		$this->save(new Privilege('C4E19AFC-14CA-9714-B0E6-B1354EC0571C', Privilege::EVENTPARTICIPENT, true));
		$this->save(new Privilege('26F7145B-826A-F731-4F59-E435B2E94F81', Privilege::EVENTMANAGER, false));
		$this->save(new Privilege('9941EE1E-6E61-0656-E72B-18A4EE48633C', Privilege::EVENTADMIN, false));
		$this->save(new Privilege('E2CA260A-FFA1-09D3-6C31-F32F231454F9', Privilege::MASTERDATAADMIN, false));
	}
	
}