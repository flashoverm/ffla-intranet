<?php

require_once "BaseDAO.php";

class LogbookDAO extends BaseDAO{
	
	const ORDER_USER = "firstname";
	const ORDER_ACTION = "action";
	const ORDER_TIMESTAMP = "timestamp";
	
	function __construct(PDO $pdo) {
		parent::__construct($pdo, "logbook");
	}
	
	function save(LogbookEntry $entry){
		$uuid = $this->generateUuid();
		$entry->setUuid($uuid);
		
		$statement = $this->db->prepare("INSERT INTO logbook (uuid, timestamp, action, object, user, message)
			VALUES(?, ?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($uuid, $entry->getTimestamp(), $entry->getAction(), $entry->getObject(), $entry->getUser(), $entry->getMessage()));

		if ($result) {

			return $entry->getUuid();
		} else {
			return false;
		}
	}

	function getLogbook(array $getParams){
		$query = "SELECT logbook.*, user.firstname FROM logbook, user WHERE user.uuid = logbook.user ORDER BY timestamp DESC";
		
		return $this->executeQuery($query, null, $getParams);
	}
	
	function getLogbookByUser($userUuid, array $getParams){
		$query = "SELECT logbook.*, user.firstname FROM logbook
			LEFT JOIN user ON user.uuid = logbook.user 
			WHERE logbook.user = ? OR logbook.object = ?
			ORDER BY timestamp DESC";
		
		return $this->executeQuery($query, array($userUuid, $userUuid), $getParams);
	}
	
	function clearLogbook(){
		$statement = $this->db->prepare("DELETE FROM logbook");
		
		if ($statement->execute()) {
			return true;
		}
		return false;
	}
	
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new LogbookEntry();
		$object->setUuid($result['uuid']);
		$object->setTimestamp($result['timestamp']);
		$object->setAction($result['action']);
		$object->setObjects($result['object']);
		$object->setMessage($result['message']);
		$object->setUser($result['user']);
		return $object;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE logbook (
                          uuid CHARACTER(36) NOT NULL,
						  timestamp DATETIME NOT NULL,
						  action SMALLINT NOT NULL,
						  object VARCHAR(255),
						  user CHARACTER(36),
						  message CHARACTER(255),
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			return true;
		}
		return false;
	}
}