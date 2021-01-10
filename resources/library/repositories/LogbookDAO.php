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
	
	function getLogbook(){
		$statement = $this->db->prepare("SELECT * FROM logbook ORDER BY timestamp DESC");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getLogbookPage($page, $resultSize = 20){
		$fromRowNumber = (($page-1)*$resultSize)+1;
		$toRowNumber = $fromRowNumber+$resultSize;
		
		$this->db->query("SET @row_number = 0;");
		
		$statement = $this->db->prepare("
			SELECT  *
			FROM ( SELECT *, (@row_number:=@row_number + 1) AS RowNum FROM logbook ORDER BY timestamp DESC) AS Data
			WHERE   RowNum >= ? AND RowNum < ?
			ORDER BY RowNum");
		
		if ($statement->execute(array($fromRowNumber, $toRowNumber))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getLogbookEntryCount(){
		$statement = $this->db->prepare("SELECT count(*) AS count FROM logbook");
		
		if ($statement->execute()) {
			return $statement->fetchColumn(); 
		}
		return false;
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