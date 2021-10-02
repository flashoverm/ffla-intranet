<?php

require_once "BaseDAO.php";

class LogbookDAO extends BaseDAO{
	
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

	function getLogbookPage($page, $resultSize = 20){
		$query = "SELECT * FROM logbook ORDER BY timestamp DESC";
		
		return $this->executeQuery($query, null, $page, $resultSize);
	}
	
	function getLogbookEntryCount(){
		return $this->getEntryCount();
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