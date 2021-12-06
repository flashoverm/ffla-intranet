<?php

require_once "BaseDAO.php";

class MailLogDAO extends BaseDAO{
	
	const ORDER_SUBJECT = "subject";
	const ORDER_RECIPIENT = "recipient";
	const ORDER_TIMESTAMP = "timestamp";
	const ORDER_STATE = "state";
	
	function __construct(PDO $pdo) {
		parent::__construct($pdo, "maillog");
	}
	
	function save(MailLog $log){
		$uuid = $this->generateUuid();
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
	
	function getMailLogs(array $getParams){
		$query = "SELECT * FROM maillog ORDER BY timestamp DESC";
		
		return $this->executeQuery($query, null, $getParams);
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
	
	protected function createTable() {
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
		}
		return false;
	}
	
}