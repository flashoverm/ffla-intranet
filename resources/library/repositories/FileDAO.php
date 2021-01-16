<?php 

require_once "BaseDAO.php";

class FileDAO extends BaseDAO {
	
	function __construct(PDO $pdo) {
		global $config;
		parent::__construct($pdo, "file");
		
		$files = scandir($config['paths']['initial'] . "files/");
		foreach($files as $file){
			if( ! file_exists($config['paths']['files'] . $file) ){
				copy($config['paths']['initial'] . "files/" . $file,
						$config['paths']['files'] . $file);
			}
		}
	}
	
	function save(File $file){
		$uuid = $this->generateUuid();
		
		$statement = $this->db->prepare("INSERT INTO file (uuid, description, date, filename) VALUES (?, ?, ?, ?)");
		
		$result = $statement->execute(array($uuid, $file->getDescription(), $file->getDate(), $file->getFilename()));
		
		if ($result) {
			return $this->getFile($uuid);
			
		} else {
			return false;
		}
	}
	
	function getFile($uuid){
		$statement = $this->db->prepare("SELECT * FROM file WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getFiles(){
		$statement = $this->db->prepare("SELECT * FROM file ORDER BY description");
		
		if ($statement->execute(array())) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function deleteFile($uuid){
		$statement = $this->db->prepare("DELETE FROM file WHERE uuid= ?");
		
		if ($statement->execute(array($uuid))) {
			return true;
		}
		return false;
	}
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new File();
		$object->setUuid($result['uuid']);
		$object->setDate($result['date']);
		$object->setDescription($result['description']);
		$object->setFilename($result['filename']);
		return $object;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE file (
						  uuid CHARACTER(36) NOT NULL,
                          description VARCHAR(255) NOT NULL,
                          date DATETIME  NOT NULL,
						  filename VARCHAR(255) NOT NULL,
                          PRIMARY KEY  (uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			return true;
		}
		return false;
	}
	
}