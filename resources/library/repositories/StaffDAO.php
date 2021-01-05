<?php

require_once "BaseDAO.php";

class StaffDAO extends BaseDAO{
	
	protected $userDAO;
	protected $staffPositionDAO;
	
	function __construct(PDO $pdo, UserDAO $userDAO, StaffPositionDAO $staffPositionDAO) {
		parent::__construct($pdo, "staff");
		$this->userDAO = $userDAO;
		$this->staffPositionDAO = $staffPositionDAO;
	}
	
	function save(Staff $staff){
		$saved = null;
		if($this->uuidExists($staff->getUuid(), $this->tableName)){
			$saved = $this->updateEventStaffEntry($staff);
		} else {
			$saved = $this->insertEvent($staff);
		}
		if($saved != null){
			return $saved;
		}
		return false;
	}
	
	function getEventStaffEntry($staffUuid){
		$statement = $this->db->prepare("SELECT * FROM staff WHERE uuid = ?");
		
		if ($statement->execute(array($staffUuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getEventStaff($eventUuid){
		$statement = $this->db->prepare("SELECT * FROM staff WHERE event = ?");
		
		if ($statement->execute(array($eventUuid))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function deleteEventStaffEntry($staffEntryUuid){
		$statement = $this->db->prepare("DELETE FROM staff WHERE uuid= ?");
		
		if ($statement->execute(array($staffEntryUuid))) {
			return true;
		}
		return false;
	}
	
	
	
	/*
	 * Init and helper methods
	 */

	
	protected function resultToObject($result){
		$object = new Staff();
		$object->setUuid($result['uuid']);
		$object->setPosition($this->staffPositionDAO->getStaffPosition($result['position']));
		if($result['user']){
			$object->setUser($this->userDAO->getUserByUUID($result['user']));
		}
		$object->setUnconfirmed($result['unconfirmed']);
		$object->setEventUuid($result['event']);
		return $object;
	}
	
	protected function insertEventStaffEntry(Staff $staff){
		$uuid = getUuid ();
		
		$statement = $this->db->prepare("INSERT INTO staff (uuid, position, event, user, unconfirmed) VALUES (?, ?, ?, NULL, ?)");
		
		$result = $statement->execute(array($uuid, $staff->getPosition()->getUuid(),
				$staff->getEventUuid(), $staff->getUnconfirmed()
		));
		
		if($result){
			return $this->getEventStaffEntry($staff->getUuid());
		}
		return false;
	}
	
	protected function updateEventStaffEntry(Staff $staff){
		$user = $staff->getUser();
		if($user != NULL){
			$user = $user->getUuid();
		}
		
		$statement = $this->db->prepare("UPDATE staff
		SET position = ?, user = ?, unconfirmed = ?, event = ?
		WHERE uuid = ?");
		
		$result = $statement->execute(array($staff->getPosition()->getUuid(),
				$user, $staff->getUnconfirmed(), $staff->getEventUuid(), $staff->getUuid()
		));
		if($result){
			return $staff;
		}
		return false;
	}
	
	protected function eventStaffEntryExists(Staff $staff){
		if($staff->getUuid() == NULL){
			return false;
		}
		if( $this->getEventStaffEntry($staff->getUuid()) ){
			return true;
		}
		return false;
	}
	
	protected function createTable(){
		$statement = $this->db->prepare("CREATE TABLE staff (
						  uuid CHARACTER(36) NOT NULL,
                          position CHARACTER(36) NOT NULL,
                          event CHARACTER(36) NOT NULL,
						  user CHARACTER(36),
						  unconfirmed BOOLEAN NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid),
						  FOREIGN KEY (event) REFERENCES event(uuid),
                          FOREIGN KEY (position) REFERENCES staffposition(uuid)
                          )");
		$result = $statement->execute();
		
		if($result){
			return true;
		}
		return false;
	}
		
	
}