<?php

require_once "BaseDAO.php";

class ConfirmationDAO extends BaseDAO {
	
	const ORDER_DATE = "date";
	const ORDER_START = "start_time";
	const ORDER_END = "end_time";
	const ORDER_ALARM = "description";
	const ORDER_USER = "firstname";
	const ORDER_ENGINE = "name";
	const ORDER_REASON = "reason";
	const ORDER_LASTUPDATE = "last_update";
	
	protected $userDAO;
	
	public function __construct(PDO $pdo, UserDAO $userDAO) {
		parent::__construct($pdo, "confirmation");
		$this->userDAO = $userDAO;
	}
	
	public function save(Confirmation $confirmation){
		$saved = null;
		if($this->uuidExists($confirmation->getUuid(), $this->tableName)){
			$saved = $this->updateConfirmation($confirmation);
		} else {
			$saved = $this->insertConfirmation($confirmation);
		}
		if($saved != null){
			return $saved;
		}
		return false;
	}
	
	public function getConfirmation($uuid){
		$statement = $this->db->prepare("SELECT * FROM confirmation WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	public function getConfirmations(array $getParams){
		$query = "SELECT confirmation.*, user.firstname, engine.name FROM confirmation, user, engine
			WHERE confirmation.user = user.uuid AND user.engine = engine.uuid";
		
		return $this->executeQuery($query, null, $getParams);
	}
	
	public function getConfirmationsByState($state, $getParams){
		$query = "SELECT confirmation.*, user.firstname, engine.name FROM confirmation, user, engine
			WHERE state = ? AND confirmation.user = user.uuid AND user.engine = engine.uuid
			ORDER BY date DESC, start_time DESC";
		
		return $this->executeQuery($query, array($state), $getParams);
	}
	
	public function getConfirmationsByStateAndEngine($state, $engineUuid, $getParams){
	    $query = "SELECT confirmation.*, user.firstname, engine.name FROM confirmation, user, engine
			WHERE state = ?
            AND confirmation.user = user.uuid
            AND user.engine = engine.uuid
            AND engine.uuid = ?
			ORDER BY date DESC, start_time DESC";
	    
	    return $this->executeQuery($query, array($state, $engineUuid), $getParams);
	}
	
	public function getConfirmationsByStateAndEngineAndAssignee($state, $engineUuid, $userUuid, $getParams){
	    $query = "SELECT confirmation.*, user.firstname, engine.name FROM confirmation, user, engine
			WHERE state = ?
            AND confirmation.user = user.uuid
            AND user.engine = engine.uuid
            AND (engine.uuid = ? OR confirmation.assigned_to = ? )
			ORDER BY date DESC, start_time DESC";
	    
	    return $this->executeQuery($query, array($state, $engineUuid, $userUuid), $getParams);
	}
	
	public function getConfirmationsByStateAndUser($state, $userUuid, $getParams){
		$query = "SELECT confirmation.*, user.firstname, engine.name FROM confirmation, user, engine
			WHERE user = ? AND state = ? AND confirmation.user = user.uuid AND user.engine = engine.uuid
			ORDER BY date DESC, start_time DESC";
		
		return $this->executeQuery($query, array($userUuid, $state), $getParams);
	}
	
	public function deleteConfirmation($uuid){
		$statement = $this->db->prepare("DELETE FROM confirmation WHERE uuid= ?");
		
		if ($statement->execute(array($uuid))) {
			return true;
		}
		return false;
	}
	
	/*
	 * Init and helper methods
	 */
	
	protected function insertConfirmation(Confirmation $confirmation){
		$uuid = $this->generateUuid();
		$confirmation->setUuid($uuid);
		
		$assigendToUuid = null;
		if($confirmation->getAssignedTo() != null){
		    $assigendToUuid = $confirmation->getAssignedTo()->getUuid();
		}
		
		$statement = $this->db->prepare("INSERT INTO confirmation (
        uuid, date, start_time, end_time, description, state, user, assigned_to, last_update)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($uuid, $confirmation->getDate(),
		    $confirmation->getStartTime(), $confirmation->getEndTime(),
			$confirmation->getDescription(), $confirmation->getState(),
		    $confirmation->getUser()->getUuid(), $assigendToUuid, date('Y-m-d H:i:s')
		));
		
		if ($result) {
			return $this->getConfirmation($uuid);
			
		} else {
			return false;
		}
	}
	
	protected function updateConfirmation(Confirmation $confirmation){
		$lastAdvisorUuid = null;
		if($confirmation->getLastAdvisor() != null){
			$lastAdvisorUuid = $confirmation->getLastAdvisor()->getUuid();
		}
		$assigendToUuid = null;
		if($confirmation->getAssignedTo() != null){
		    $assigendToUuid = $confirmation->getAssignedTo()->getUuid();
		}
				
		$statement = $this->db->prepare("UPDATE confirmation
		SET date = ?, start_time = ?, end_time = ?, description = ?, state = ?, reason = ?,
        user = ?, assigned_to = ?, last_advisor = ?, last_update = ?
		WHERE uuid= ?");
		
		$result = $statement->execute(array($confirmation->getDate(), $confirmation->getStartTime(),
		    $confirmation->getEndTime(), $confirmation->getDescription(),
		    $confirmation->getState(), $confirmation->getReason(),
		    $confirmation->getUser()->getUuid(), $assigendToUuid,
		    $lastAdvisorUuid, date('Y-m-d H:i:s'), $confirmation->getUuid()
		));
		
		if ($result) {
			return $confirmation;
		} else {
			return false;
		}
	}
	
	protected function resultToObject($result){
		$object = new Confirmation();
		$object->setUuid($result['uuid']);
		$object->setDate($result['date']);
		$object->setStartTime($result['start_time']);
		$object->setEndTime($result['end_time']);
		$object->setDescription($result['description']);
		$object->setState($result['state']);
		$object->setReason($result['reason']);
		$object->setUser($this->userDAO->getUserByUUID($result['user']));
		$object->setLastUpdate($result['last_update']);
		if($result['assigned_to'] != null){
		    $object->setAssignedTo($this->userDAO->getUserByUUID($result['assigned_to']));
		}
		if($result['last_advisor'] != null){
			$object->setLastAdvisor($this->userDAO->getUserByUUID($result['last_advisor']));
		}
		return $object;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE confirmation (
						  uuid CHARACTER(36) NOT NULL,
                          date DATE  NOT NULL,
                          start_time TIME NOT NULL,
                          end_time TIME NOT NULL,
                          description VARCHAR(255) NOT NULL,
						  user CHARACTER(36) NOT NULL,
                          assigned_to CHARACTER(36) NOT NULL,
						  state TINYINT NOT NULL,
						  reason VARCHAR(255),
						  last_advisor CHARACTER(36),
						  last_update DATE,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid),
						  FOREIGN KEY (last_advisor) REFERENCES user(uuid),
                          FOREIGN KEY (assigned_to) REFERENCES user(uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			return true;
		}
		return false;
	}
}
