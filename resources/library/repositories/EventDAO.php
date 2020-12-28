<?php

require_once "BaseDAO.php";

class EventDAO extends BaseDAO{
	
	protected $userDAO;
	protected $engineDAO;
	protected $staffPositionDAO;
	protected $eventTypeDAO;
	
	function __construct() {
		parent::__construct();
		$this->userDAO = new UserDAO();
		$this->engineDAO = new EngineDAO();
		$this->staffPositionDAO = new StaffPositionDAO();
		$this->eventTypeDAO = new EventTypeDAO();
	}
	
	function save(Event $event){
		$saved = null;
		if($this->uuidExists($event->getUuid(), "event")){
			$saved = $this->updateEvent($event);
		} else {
			$saved = $this->insertEvent($event);
		}
		if($saved != null){
			return $saved;
		}
		return false;
	}
	
	function updateEventStaffEntry(Staff $staff){
		$user = $staff->getUser();
		if($user != NULL){
			$user = $user->getUuid();
		}
		
		$statement = $this->db->prepare("UPDATE staff
		SET position = ?, user = ?, unconfirmed = ?
		WHERE uuid = ?");
		
		$result = $statement->execute(array($staff->getPosition()->getUuid(),
				$user, $staff->getUnconfirmed(), $staff->getUuid()
		));
		if($result){
			return true;
		}
		return false;
	}
	
	function getEvent($eventUuid){
		$statement = $this->db->prepare("SELECT * FROM event WHERE uuid = ?");
		
		if ($statement->execute(array($eventUuid))) {
			return $this->handleResult($statement, false);
		}
		return false;	
	}
	
	function getEventStaff($eventUuid){
		$statement = $this->db->prepare("SELECT * FROM staff WHERE event = ?");
		
		if ($statement->execute(array($eventUuid))) {
			$objects = array();
			while($row = $statement->fetch()) {
				$objects [] = $this->resultToStaffObject($row);
			}
			return $objects;
		}
		return false;
	}
	
	function getEventStaffEntry($staffUuid){
		$statement = $this->db->prepare("SELECT * FROM staff WHERE uuid = ?");
		
		if ($statement->execute(array($staffUuid))) {
			return $this->resultToStaffObject($statement->fetch());
		}
		return false;
	}
	
	function getPublicEvents(){
		$statement = $this->db->prepare("SELECT * FROM event WHERE date >= (now() - INTERVAL 1 DAY) AND published = TRUE AND deleted_by IS NULL ORDER BY date DESC");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;	
	}
	
	function getActiveEvents(){
		$statement = $this->db->prepare("SELECT * FROM event WHERE date >= (now() - INTERVAL 1 DAY) AND deleted_by IS NULL ORDER BY date ASC");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;	
	}
	
	function getPastEvents(){
		$statement = $this->db->prepare("SELECT * FROM event WHERE date < (now() - INTERVAL 1 DAY) AND deleted_by IS NULL ORDER BY date ASC");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;	
	}
	
	function getDeletedEvents(){
		$statement = $this->db->prepare("SELECT * FROM event WHERE NOT deleted_by IS NULL ORDER BY date ASC");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;	
	}
	
	function getUsersActiveEvents(User $user){
		$statement = $this->db->prepare("SELECT * FROM event WHERE (engine = ? OR creator = ?) AND date >= (now() - INTERVAL 1 DAY) AND deleted_by IS NULL ORDER BY date ASC");
		
		if ($statement->execute(array($user->getEngine()->getUuid(), $user->getUuid()))) {
			return $this->handleResult($statement, true);
		}
		return false;	
	}
	
	function getUsersPastEvents(User $user){
		$statement = $this->db->prepare("SELECT * FROM event WHERE (engine = ? OR creator = ?) AND date < (now() - INTERVAL 1 DAY) AND deleted_by IS NULL ORDER BY date DESC");
		
		if ($statement->execute(array($user->getEngine()->getUuid(), $user->getUuid()))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function isUserAlreadyStaff($eventUuid, $userUuid){
		$statement = $this->db->prepare("SELECT * FROM staff WHERE event = ? AND user = ?");
		
		if ($statement->execute(array($eventUuid, $userUuid))) {
			return $statement->num_rows;
		}
		return false;
	}

	function deleteEvent($eventUuid){
		$statement = $this->db->prepare("DELETE FROM staff WHERE event = ?");
		
		if ($statement->execute(array($eventUuid))) {
			
			$statement = $this->db->prepare("DELETE FROM event WHERE uuid= ?");
			
			if ($statement->execute(array($eventUuid))) {
				return true;
			}
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
		$object = new Event();
		$object->setUuid($result['uuid']);
		$object->setDate($result['date']);
		$object->setStartTime($result['start_time']);
		$object->setEndTime($result['end_time']);
		$object->setTitle($result['title']);
		$object->setType($this->eventTypeDAO->getEventType($result['type']));
		$object->setTypeOther($result['type_other']);
		$object->setComment($result['comment']);
		$object->setPublished($result['published']);
		$object->setStaffConfirmation($result['staff_confirmation']);
		$object->setHash($result['hash']);
		$object->setCreator($this->userDAO->getUserByUUID($result['creator']));
		$object->setEngine($this->engineDAO->getEngine($result['engine']));
		if($result['deleted_by']){
			$object->setDeletedBy($this->userDAO->getUserByUUID($result['deleted_by']));
		}
		$object->setStaff($this->getEventStaff($result['uuid']));
		return $object;
	}
	
	protected function resultToStaffObject($result){
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
	
	protected function insertEvent(Event $event){
		$uuid = getUuid ();
		$hash = hash ( "sha256", $uuid . $event->getDate() . $event->getStarTime() 
				. $event->getEndTime() . $event->getType()->getUuid() . $event->getTitle() );
		
		$statement = $this->db->prepare("INSERT INTO event 
		(uuid, date, start_time, end_time, type, type_other, title, comment, engine, creator, published, staff_confirmation, deleted_by, hash)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?)");
		
		$result = $statement->execute(array($uuid, $event->getDate(), $event->getStarTime(), 
				$event->getEndTime(), $event->getType()->getUuid(), $event->getTypeOther(), 
				$event->getTitle(), $event->getComment(), $event->getEngine()->getUuid(), 
				$event->getCreator()->getUuid(), $event->getPublished(), 
				$event->getStaffConfirmation(), $hash
		));
		
		if ($result) {
			foreach($event->getStaff() as $staff){
				$this->insertEventStaffEntry($uuid, $staff);
			}
			return $this->getInspection($uuid);
		}
		return false;
	}
	
	protected function updateEvent(Event $event){
		$deletedBy = $event->getDeletedBy();
		if($deletedBy != NULL){
			$deletedBy = $deletedBy->getUuid();
		}
		$statement = $this->db->prepare("UPDATE event
		SET date = ?, start_time = ?, end_time = ?, type = ?, type_other = ?, title = ?, comment = ?, engine = ?, creator = ?, published = ?, staff_confirmation = ?, deleted_by = ?, hash = ?
		WHERE uuid = ?");
		
		$result = $statement->execute(array($event->getDate(), $event->getStartTime(),
				$event->getEndTime(), $event->getType()->getUuid(), $event->getTypeOther(),
				$event->getTitle(), $event->getComment(), $event->getEngine()->getUuid(),
				$event->getCreator()->getUuid(), $event->getPublished(),
				$event->getStaffConfirmation(), $deletedBy, $event->getHash(), $event->getUuid()
		));
		
		if ($result) {
			foreach($event->getStaff() as $staff){
				if($this->eventStaffEntryExists($staff)){
					$this->updateEventStaffEntry($staff);
				} else {
					$this->insertEventStaffEntry($event->getUuid(), $staff);
				}
			}
			return $this->getEvent($event->getUuid());
		}
		return false;
	}
	
	protected function insertEventStaffEntry($eventUuid, Staff $staff){
		$uuid = getUuid ();
		
		$statement = $this->db->prepare("INSERT INTO staff (uuid, position, event, user, unconfirmed) VALUES (?, ?, ?, NULL, ?)");
		
		$result = $statement->execute(array($uuid, $staff->getPosition()->getUuid(),
				$eventUuid, $staff->getUnconfirmed()
		));
		
		if($result){
			return true;
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
		$statement = $this->db->prepare("CREATE TABLE event (
                          uuid CHARACTER(36) NOT NULL,
						  date DATE NOT NULL,
                          start_time TIME NOT NULL,
                          end_time TIME,
                          type CHARACTER(36) NOT NULL,
                          type_other VARCHAR(96),
						  title VARCHAR(96),
						  comment VARCHAR(255),
                          engine CHARACTER(36) NOT NULL,
						  creator CHARACTER(36) NOT NULL,
                          published BOOLEAN NOT NULL,
						  staff_confirmation BOOLEAN NOT NULL,
						  deleted_by CHAR(36),
						  hash VARCHAR(64) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (creator) REFERENCES user(uuid),
						  FOREIGN KEY (type) REFERENCES eventtype(uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
		$result = $statement->execute();
		
		if ($result) {
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
		}
		return false;
	}
}