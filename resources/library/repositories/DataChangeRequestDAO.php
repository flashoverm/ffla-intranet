<?php 

require_once "BaseDAO.php";

class DataChangeRequestDAO extends BaseDAO {
	
	function __construct(PDO $pdo) {
		parent::__construct($pdo, "datachangerequest");
	}
	
	function save(DataChangeRequest $dataChangeRequest){
		$saved = null;
		if($this->uuidExists($dataChangeRequest->getUuid(), $this->tableName)){
			$saved = $this->update($dataChangeRequest);
		} else {
			$saved = $this->insert($dataChangeRequest);
		}
		if($saved != null){
			return $saved;
		}
		return false;
	}
	
	function getDataChangeRequest($uuid){
		$statement = $this->db->prepare("SELECT * FROM datachangerequest WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getDataChangeRequestsByState($state){
		$statement = $this->db->prepare("SELECT * FROM datachangerequest WHERE state = ?");
		
		if ($statement->execute(array($state))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getDataChangeRequestsByStateAndUser($state, $userUuid){
		$statement = $this->db->prepare("SELECT * FROM datachangerequest WHERE user = ? AND state = ?");
		
		if ($statement->execute(array($userUuid, $state))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function deleteDataChangeRequest($uuid){
		$statement = $this->db->prepare("DELETE FROM datachangerequest WHERE uuid= ?");
		
		if ($statement->execute(array($uuid))) {
			return true;
		}
		return false;
	}
	
	/*
	 * Init and helper methods
	 */
	
	protected function insert(DataChangeRequest $dataChangeRequest){
		$uuid = $this->getUuid();
		
		$statement = $this->db->prepare("INSERT INTO confirmation 
		(uuid, datatype, newvalue, comment, state, user, last_advisor)
		VALUES (?, ?, ?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($uuid, $dataChangeRequest->getDatatype(), 
				$dataChangeRequest->getNewValue(), $dataChangeRequest->getComment(), 
				$dataChangeRequest->getState(), $dataChangeRequest->getUser()->getUuid(), 
				null
		));
		
		if ($result) {
			return $this->getDataChangeRequest($uuid);
			
		} else {
			return false;
		}
	}
	
	protected function update(DataChangeRequest $dataChangeRequest){
		$lastAdvisorUuid = null;
		if($dataChangeRequest->getLastAdvisor() != null){
			$lastAdvisorUuid = $dataChangeRequest->getLastAdvisor()->getUuid();
		}
		
		$statement = $this->db->prepare("UPDATE confirmation
		SET datatype = ?, newvalue = ?, comment = ?, state = ?, user = ?, last_advisor = ?
		WHERE uuid= ?");
		
		$result = $statement->execute(array($dataChangeRequest->getDatatype(), $dataChangeRequest->getNewValue(), 
				$dataChangeRequest->getComment(), $dataChangeRequest->getState(), 
				$dataChangeRequest->getUser()->getUuid(), $lastAdvisorUuid, $dataChangeRequest->getUuid()
		));
		
		if ($result) {
			return $dataChangeRequest;
		} else {
			return false;
		}
	}
	
	protected function resultToObject($result){
		$object = new DataChangeRequest();
		$object->setUuid($result['uuid']);
		$object->setDatatype($result['datatype']);
		$object->setNewValue($result['newvalue']);
		$object->setState($result['state']);
		$object->setComment($result['comment']);
		$object->setUser($this->userDAO->getUserByUUID($result['user']));
		
		if($result['last_advisor'] != NULL){
			$object->setLastAdvisor($this->userDAO->getUserByUUID($result['last_advisor']));
		}
		return $object;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE datachangerequest (
						  uuid CHARACTER(36) NOT NULL,
						  datatype TINYINT NOT NULL,
                          newvalue VARCHAR(255) NOT NULL,
						  comment VARCHAR(255),
						  state TINYINT NOT NULL,
						  user CHARACTER(36) NOT NULL,
						  last_advisor CHARACTER(36),
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