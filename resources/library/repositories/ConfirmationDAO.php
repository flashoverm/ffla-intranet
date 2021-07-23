<?php 

require_once "BaseDAO.php";

class ConfirmationDAO extends BaseDAO {
	
	protected $userDAO;
	
	function __construct(PDO $pdo, UserDAO $userDAO) {
		parent::__construct($pdo, "confirmation");
		$this->userDAO = $userDAO;
	}
	
	function save(Confirmation $confirmation){
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
	
	function getConfirmation($uuid){
		$statement = $this->db->prepare("SELECT * FROM confirmation WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getConfirmations(){
		$statement = $this->db->prepare("SELECT * FROM confirmation ORDER BY date DESC, start_time DESC");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getConfirmationsByState($state){
		$statement = $this->db->prepare("SELECT * FROM confirmation WHERE state = ? ORDER BY date DESC, start_time DESC");
		
		if ($statement->execute(array($state))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getConfirmationsByStateAndUser($state, $userUuid){
		$statement = $this->db->prepare("SELECT * FROM confirmation WHERE user = ? AND state = ? ORDER BY date DESC, start_time DESC");
		
		if ($statement->execute(array($userUuid, $state))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function deleteConfirmation($uuid){
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
		
		$statement = $this->db->prepare("INSERT INTO confirmation (uuid, date, start_time, end_time, description, state, user, last_update)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($uuid, $confirmation->getDate(), $confirmation->getStartTime(), $confirmation->getEndTime(),
				$confirmation->getDescription(), $confirmation->getState(), $confirmation->getUser()->getUuid(), date('Y-m-d H:i:s')
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
				
		$statement = $this->db->prepare("UPDATE confirmation 
		SET date = ?, start_time = ?, end_time = ?, description = ?, state = ?, reason = ?, user = ?, last_advisor = ?, last_update = ?
		WHERE uuid= ?");
		
		$result = $statement->execute(array($confirmation->getDate(), $confirmation->getStartTime(), $confirmation->getEndTime(),
				$confirmation->getDescription(), $confirmation->getState(), $confirmation->getReason(), 
				$confirmation->getUser()->getUuid(), $lastAdvisorUuid, date('Y-m-d H:i:s'), $confirmation->getUuid()
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
		if($result['last_advisor'] != NULL){
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
						  state TINYINT NOT NULL,
						  reason VARCHAR(255),
						  last_advisor CHARACTER(36),
						  last_update DATE,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid),
						  FOREIGN KEY (last_advisor) REFERENCES user(uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			return true;
		}
		return false;
	}
}