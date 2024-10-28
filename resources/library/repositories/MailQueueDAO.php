<?php

require_once "BaseDAO.php";

class MailQueueDAO extends BaseDAO{
    
    function __construct(PDO $pdo) {
        parent::__construct($pdo, "mailqueue");
    }
    
    function save(MailQueueElement $mail){
        $uuid = $this->generateUuid();
        $mail->setUuid($uuid);
        
        $statement = $this->db->prepare("INSERT INTO mailqueue (uuid, timestamp, retries, recipient, subject, body)
			VALUES (?, ?, ?, ?, ?, ?)");
        
        $result = $statement->execute(array($mail->getUuid(), $mail->getTimestamp(), $mail->getRetries(),
            $mail->getRecipient(), $mail->getSubject(), $mail->getBody()));
        
        if ($result) {
            
            return $mail->getUuid();
        } else {
            var_dump($statement->errorInfo());
            return false;
        }
    }
    
    function retry(MailQueueElement $mail){
        $mail->retry();
        
        $statement = $this->db->prepare("UPDATE mailqueue
		SET timestamp = ?, retries = ?
		WHERE uuid = ?");
        
        $result = $statement->execute(array($mail->getTimestamp(), $mail->getRetries(), $mail->getUuid()));
        
        if ($result) {
            return $mail;
        } else {
            return false;
        }
    }
    
    function getOldestEntry(){
        $statement = $this->db->prepare("SELECT * FROM mailqueue ORDER BY timestamp ASC LIMIT 1");
        
        if ($statement->execute()) {
            return $this->handleResult($statement, false);
        }
        return false;
    }
    
    function delete(MailQueueElement $mail){
        $statement = $this->db->prepare("DELETE FROM mailqueue WHERE uuid = ?");
        
        if ($statement->execute(array($mail->getUuid()))) {
            return true;
        }
        return false;
    }
    
    protected function resultToObject($result){
        $object = new MailQueueElement();
        $object->setUuid($result['uuid']);
        $object->setTimestamp($result['timestamp']);
        $object->setRetries($result['retries']);
        $object->setRecipient($result['recipient']);
        $object->setSubject($result['subject']);
        $object->setBody($result['body']);
        return $object;
    }
    
    protected function createTable() {
        $statement = $this->db->prepare("CREATE TABLE mailqueue (
						  uuid CHARACTER(36) NOT NULL,
                          timestamp datetime NOT NULL,
                          retries TINYINT NOT NULL,
						  recipient VARCHAR(255) NOT NULL,
						  subject VARCHAR(255) NOT NULL,
						  body TEXT,
                          PRIMARY KEY (uuid)
                          )");
        
        $result = $statement->execute();
        
        if ($result) {
            return true;
        }
        return false;
    }
}