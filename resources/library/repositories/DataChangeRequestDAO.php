<?php 

require_once "BaseDAO.php";

class DataChangeRequestDAO extends BaseDAO {
	
	const ORDER_DATE = "createdate";
	const ORDER_TYPE = "datatype";
	const ORDER_NEWVALUE = "newvalue";
	const ORDER_PERSON = "person";
	const ORDER_USER = "firstname";
	const ORDER_ENGINE = "name";
	const ORDER_LASTUPDATE = "last_update";
	
	protected $userDAO;
	
	function __construct(PDO $pdo, UserDAO $userDAO) {
		parent::__construct($pdo, "datachangerequest");
		$this->userDAO = $userDAO;
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
	
	function getDataChangeRequestsByState($state, array $getParams){
		$query = "SELECT datachangerequest.*, user.firstname, engine.name  FROM datachangerequest, user, engine 
			WHERE state = ? AND datachangerequest.user = user.uuid AND user.engine = engine.uuid
			ORDER BY createdate DESC";
		
		return $this->executeQuery($query, array($state), $getParams);
	}
	
	function getDataChangeRequestsByStateAndUser($state, $userUuid, array $getParams){
		$query = "SELECT datachangerequest.*, user.firstname, engine.name  FROM datachangerequest, user, engine 
			WHERE user = ? AND state = ? AND datachangerequest.user = user.uuid AND user.engine = engine.uuid
			ORDER BY createdate DESC";
		
		return $this->executeQuery($query, array($userUuid, $state), $getParams);
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
		$uuid = $this->generateUuid();
		$dataChangeRequest->setUuid($uuid);
		
		$statement = $this->db->prepare("INSERT INTO datachangerequest 
		(uuid, createdate, datatype, newvalue, comment, state, person, user, last_advisor, last_update)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($uuid, $dataChangeRequest->getCreateDate(),
				$dataChangeRequest->getDatatype(), $dataChangeRequest->getNewValue(), 
				$dataChangeRequest->getComment(), $dataChangeRequest->getState(), 
				$dataChangeRequest->getPerson(), 
				$dataChangeRequest->getUser()->getUuid(), null, date('Y-m-d H:i:s')
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
		
		$statement = $this->db->prepare("UPDATE datachangerequest
		SET createdate = ?, datatype = ?, newvalue = ?, comment = ?, state = ?, person = ?, user = ?, 
		last_advisor = ?, further_request = ?, last_update = ?
		WHERE uuid= ?");
		
		$result = $statement->execute(array( $dataChangeRequest->getCreateDate(),$dataChangeRequest->getDatatype(), 
				$dataChangeRequest->getNewValue(), $dataChangeRequest->getComment(), $dataChangeRequest->getState(),
				$dataChangeRequest->getPerson(), $dataChangeRequest->getUser()->getUuid(), $lastAdvisorUuid, 
				$dataChangeRequest->getFurtherRequest(), date('Y-m-d H:i:s'), $dataChangeRequest->getUuid()
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
		$object->setCreateDate($result['createdate']);
		$object->setDatatype($result['datatype']);
		$object->setNewValue($result['newvalue']);
		$object->setState($result['state']);
		$object->setComment($result['comment']);
		$object->setUser($this->userDAO->getUserByUUID($result['user']));
		$object->setPerson($result['person']);
		$object->setFurtherRequest($result['further_request']);
		$object->setLastUpdate($result['last_update']);
		
		if($result['last_advisor'] != NULL){
			$object->setLastAdvisor($this->userDAO->getUserByUUID($result['last_advisor']));
		}
		return $object;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE datachangerequest (
						  uuid CHARACTER(36) NOT NULL,
						  createdate DATETIME NOT NULL,
						  datatype TINYINT NOT NULL,
                          newvalue VARCHAR(255) NOT NULL,
						  comment VARCHAR(255),
						  state TINYINT NOT NULL,
						  person VARCHAR(255),
						  user CHARACTER(36) NOT NULL,
						  further_request VARCHAR(255),
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