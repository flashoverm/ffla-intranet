<?php

require_once "BaseDAO.php";

class EventDAO extends BaseDAO{
	
	const ORDER_DATE = "date";
	const ORDER_START = "start_time";
	const ORDER_END = "end_time";
	const ORDER_TYPE = "type";
	const ORDER_TITLE = "title";
	const ORDER_ENGINE = "name";
	const ORDER_PUBLIC = "published";
	
	protected $userDAO;
	protected $engineDAO;
	protected $eventTypeDAO;
	protected $staffDAO;
	
	function __construct(PDO $pdo, UserDAO $userDAO, EngineDAO $engineDAO, EventTypeDAO $eventTypeDAO, StaffDAO $staffDAO) {
		parent::__construct($pdo, "event");
		$this->userDAO = $userDAO;
		$this->engineDAO = $engineDAO;
		$this->eventTypeDAO = $eventTypeDAO;
		$this->staffDAO = $staffDAO;
	}
	
	function save(Event $event){
		$saved = null;
		if($this->uuidExists($event->getUuid(), $this->tableName)){
			$saved = $this->updateEvent($event);
		} else {
			$saved = $this->insertEvent($event);
		}
		if($saved != null){
			return $saved;
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

	function getPublicEvents(array $getParams){
		$query = "SELECT event.*, engine.name FROM event, engine 
			WHERE date >= (now() - INTERVAL 1 DAY) AND published = TRUE AND canceled_by IS NULL AND event.engine = engine.uuid
			ORDER BY date ASC";
		
		return $this->executeQuery($query, null, $getParams);
	}
	
	function getActiveEvents(array $getParams){
		$query = "SELECT event.*, engine.name FROM event, engine 
			WHERE date >= (now() - INTERVAL 1 DAY) AND canceled_by IS NULL AND event.engine = engine.uuid
			ORDER BY date ASC";
		
		return $this->executeQuery($query, null, $getParams);
	}
	
	function getEventsWithCreator($userUuid){
		$statement = $this->db->prepare("SELECT * FROM event WHERE creator = ?");
		
		if ($statement->execute(array($userUuid))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getPastEvents(array $getParams){
		$query = "SELECT event.*, engine.name FROM event, engine 
			WHERE date < (now() - INTERVAL 1 DAY) AND canceled_by IS NULL  AND event.engine = engine.uuid
			ORDER BY date DESC";
		
		return $this->executeQuery($query, null, $getParams);
	}
	
	function getCanceledEvents(array $getParams){
		$query = "SELECT event.*, engine.name FROM event, engine 
			WHERE NOT canceled_by IS NULL  AND event.engine = engine.uuid
			ORDER BY date DESC";
		
		return $this->executeQuery($query, null, $getParams);
	}
	
	function getUsersActiveEvents(User $user, array $getParams){
		$query = "SELECT event.*, engine.name
				 FROM event, engine
				 WHERE (published = TRUE OR engine = ? OR creator = ? OR engine IN (SELECT engine FROM additional_engines WHERE user = ?) OR event.uuid IN (SELECT event FROM staff WHERE user = ?) )
                 AND date >= (now() - INTERVAL 1 DAY) 
				 AND canceled_by IS NULL 
 				 AND event.engine = engine.uuid
                 ORDER BY date ASC";
				
		return $this->executeQuery($query, array($user->getEngine()->getUuid(), $user->getUuid(), $user->getUuid(), $user->getUuid()), $getParams);
	}
	
	function getUsersPastEvents(User $user, array $getParams){
		$query = "SELECT event.*, engine.name
				FROM event, engine
				WHERE (published = TRUE OR engine = ? OR creator = ? OR engine IN (SELECT engine FROM additional_engines WHERE user = ?) OR event.uuid IN (SELECT event FROM staff WHERE user = ?) ) 
				AND date < (now() - INTERVAL 1 DAY) 
				AND canceled_by IS NULL 
				AND event.engine = engine.uuid
				ORDER BY date DESC";
		
		return $this->executeQuery($query, array($user->getEngine()->getUuid(), $user->getUuid(), $user->getUuid(), $user->getUuid()), $getParams);
	}
	
	function getUsersCanceledEvents(User $user, array $getParams){
	    $query = "SELECT event.*, engine.name
				FROM event, engine
				WHERE (published = TRUE OR engine = ? OR creator = ? OR engine IN (SELECT engine FROM additional_engines WHERE user = ?) OR event.uuid IN (SELECT event FROM staff WHERE user = ?) )
				AND NOT canceled_by IS NULL
				AND event.engine = engine.uuid
				ORDER BY date DESC";
	    
	    return $this->executeQuery($query, array($user->getEngine()->getUuid(), $user->getUuid(), $user->getUuid(), $user->getUuid()), $getParams);
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
		if($result['canceled_by']){
			$object->setCancledBy($this->userDAO->getUserByUUID($result['canceled_by']));
		}
		$object->setCancelationReason($result['cancelationReason']);
		$object->setStaff($this->staffDAO->getEventStaff($result['uuid']));
		return $object;
	}
	
	protected function insertEvent(Event $event){
		$uuid = $this->generateUuid();
		$event->setUuid($uuid);
		
		$hash = hash ( "sha256", $uuid . $event->getDate() . $event->getStartTime() 
				. $event->getEndTime() . $event->getType()->getUuid() . $event->getTitle() );
		
		$statement = $this->db->prepare("INSERT INTO event 
		(uuid, date, start_time, end_time, type, type_other, title, comment, engine, creator, published, staff_confirmation, canceled_by, hash)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?)");
		
		$result = $statement->execute(array($uuid, $event->getDate(), $event->getStartTime(), 
				$event->getEndTime(), $event->getType()->getUuid(), $event->getTypeOther(), 
				$event->getTitle(), $event->getComment(), $event->getEngine()->getUuid(), 
				$event->getCreator()->getUuid(), $event->getPublished(), 
				$event->getStaffConfirmation(), $hash
		));
		
		if ($result) {
			foreach($event->getStaff() as $staff){
				if( $staff->getEventUuid() == NULL ){
					$staff->setEventUuid($event->getUuid());
				}
				if( ! $this->staffDAO->save($staff)){
					return false;
				}
			}
			return $this->getEvent($uuid);
		}
		return false;
	}
	
	protected function updateEvent(Event $event){
		$canceledBy = $event->getCancledBy();
		if($canceledBy != NULL){
		    $canceledBy = $canceledBy->getUuid();
		}
		$statement = $this->db->prepare("UPDATE event
		SET date = ?, start_time = ?, end_time = ?, type = ?, type_other = ?, title = ?, comment = ?, engine = ?, creator = ?, published = ?, staff_confirmation = ?, canceled_by = ?, cancelationReason = ?, hash = ?
		WHERE uuid = ?");
		
		$result = $statement->execute(array($event->getDate(), $event->getStartTime(),
				$event->getEndTime(), $event->getType()->getUuid(), $event->getTypeOther(),
				$event->getTitle(), $event->getComment(), $event->getEngine()->getUuid(),
				$event->getCreator()->getUuid(), $event->getPublished(),
		        $event->getStaffConfirmation(), $canceledBy, $event->getCancelationReason(), 
		        $event->getHash(), $event->getUuid()
		));
		
		if ($result) {
			foreach($event->getStaff() as $staff){
				if( $staff->getEventUuid() == NULL ){
					$staff->setEventUuid($event->getUuid());
				}
				$this->staffDAO->save($staff);
			}
			return $this->getEvent($event->getUuid());
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
                          published BOOLEAN NOT NULL default 0,
						  staff_confirmation BOOLEAN NOT NULL default 0,
						  canceled_by CHAR(36),
                          cancelationReason VARCHAR(255),
						  hash VARCHAR(64) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (creator) REFERENCES user(uuid),
						  FOREIGN KEY (type) REFERENCES eventtype(uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
		$result = $statement->execute();
		
		if ($result) {
			return true;
		}
		return false;
	}
}