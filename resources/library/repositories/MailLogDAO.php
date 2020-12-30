<?php

require_once "BaseDAO.php";

class MailLogDAO extends BaseDAO{
	
	function __construct() {
		parent::__construct();
	}
	
	function save(MailLog $log){
		$uuid = $this->getUuid();
		$log->setUuid($uuid);
		
		$statement = $this->db->prepare("INSERT INTO maillog (uuid, timestamp, recipient, subject, state, body, error)
			VALUES (?, ?, ?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($log->getUuid(), $log->getTimestamp(), $log->getRecipient(), 
				$log->getSubject(), $log->getState(), $log->getBody(), $log->getError()));
		
		if ($result) {
			
			return $log->getUuid();
		} else {
			return false;
		}
	}
	
	function getMailLogs($page, $resultSize = 20){
		$fromRowNumber = (($page-1)*$resultSize)+1;
		$toRowNumber = $fromRowNumber+$resultSize;
		
		$this->db->query("SET @row_number = 0;");
		
		$statement = $this->db->prepare("
		SELECT  *
		FROM ( SELECT *, (@row_number:=@row_number + 1) AS RowNum FROM maillog ORDER BY timestamp DESC) AS Data
		WHERE   RowNum >= ? AND RowNum < ?
		ORDER BY RowNum");
		
		if ($statement->execute(array($fromRowNumber, $toRowNumber))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getMailLogCount(){
		$statement = $this->db->prepare("SELECT count(*) AS count FROM maillog");
		
		if ($statement->execute()) {
			return $statement->fetchColumn();
		}
		return false;
	}
	
	function clearMailLog(){
		$statement = $this->db->prepare("DELETE FROM maillog");
		
		if ($statement->execute()) {
			return true;
		}
		return false;
	}
	
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new MailLog();
		$object->setUuid($result['uuid']);
		$object->setTimestamp($result['timestamp']);
		$object->setRecipient($result['recipient']);
		$object->setSubject($result['subject']);
		$object->setState($result['state']);
		$object->setBody($result['body']);
		$object->setError($result['error']);
		return $object;
	}
	
	protected function createTableMailLog() {
		$statement = $this->db->prepare("CREATE TABLE maillog (
						  uuid CHARACTER(36) NOT NULL,
                          timestamp datetime NOT NULL,
						  recipient VARCHAR(255) NOT NULL,
						  subject VARCHAR(255) NOT NULL,
						  state TINYINT NOT NULL,
						  body TEXT,
						  error VARCHAR(255),
                          PRIMARY KEY (uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
}