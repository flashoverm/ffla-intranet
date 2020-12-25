<?php 

require_once "BaseDAO.php";

class UserDAO extends BaseDAO {
	
	protected $privilegeDAO;
	protected $engineDAO;
	
	function __construct() {
		parent::__construct();
		$this->privilegeDAO = new PrivilegeDAO();
		$this->engineDAO = new EngineDAO();
	}
	
	function save(User $user){
		$saved = null;
		if($this->uuidExists($user->getUuid(), "user")){
			$saved = $this->updateUser($user);
		} else {
			$saved = $this->insertUser($user);
		}
		if($saved != null){
			$privilegeSaved = $this->privilegeDAO->saveUsersPrivilege($user);
			if( $privilegeSaved){
				return $this->getUserByUUID($saved->getUuid());
			}
		}
		return false;
	}
		
	function getUsers(){
		$statement = $this->db->prepare("SELECT * FROM user WHERE deleted = false ORDER BY email");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getUnlockedUsers(){
		$statement = $this->db->prepare("SELECT * FROM user
		WHERE locked = FALSE
		AND deleted = false
		ORDER BY lastname");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getDeletedUsers(){
		$statement = $this->db->prepare("SELECT * FROM user WHERE deleted = true ORDER BY email");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getUsersByEngine(String $engineUUID){
		$statement = $this->db->prepare("SELECT * FROM user
		WHERE engine = ?
		AND locked = FALSE
		AND deleted = false
		ORDER BY lastname");
		
		if ($statement->execute(array($engineUUID))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	
	function getUsersWithPrivilege(String $uuid){
		$statement = $this->db->prepare("SELECT user.*
		FROM user, user_privilege
		WHERE uuid = user_privilege.user AND privilege = ?
		AND user.deleted = false");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getUsersWithPrivilegeByName(String $name){
		$statement = $this->db->prepare("SELECT user.*
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND privilege.uuid = user_privilege.privilege
		AND privilege.privilege = ?
		AND user.deleted = false");
		
		if ($statement->execute(array($name))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getUsersByEngineAndPrivilege(String $engineUuid, String $privilege){
		$statement = $this->db->prepare("SELECT user.*
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND user_privilege.privilege = privilege.uuid
		AND privilege.privilege = ?
		AND engine = ?
		AND user.deleted = false");
	
		if ($statement->execute(array($privilege, $engineUuid))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
		
	function getUserByUUID(String $uuid){
		$statement = $this->db->prepare("SELECT * FROM user WHERE uuid = ?
		AND deleted = false");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement);
		}
		return false;
	}
	
	function getUserByEmail(String $email){
		$emailLower = strtolower($email);
		
		$statement = $this->db->prepare("SELECT * FROM user WHERE email = ?");
		
		if ($statement->execute(array($emailLower))) {
			return $this->handleResult($statement);
		}
		return false;
	}
	
	function getUserByData(String $firstname, String $lastname, String $email, String $engineUUID){
		$statement = $this->db->prepare("SELECT * FROM user WHERE firstname = ? AND lastname = ? AND email = ? AND engine = ? ");
		
		if ($statement->execute(array($firstname, $lastname, $email, $engineUUID))) {
			return $this->handleResult($statement);
		}
		return false;
	}
	
	
	/*
	 * Init and helper methods
	 */
	
	protected function insertUser(User $user){
		$uuid = $this->getUuid();
		$emailLower = strtolower($user->getEmail());
		
		$statement = $this->db->prepare("INSERT INTO user (
			uuid, email, firstname, lastname, password, engine, locked, employer_address, employer_mail
			) VALUES (?, ?, ?, ?, ?, ?, FALSE, ?, ?)");
		
		$result = $statement->execute(array($uuid, $emailLower, $user->getFirstname(), $user->getLastname(), $user->getPassword(),
				$user->getEngine()->getUuid(), $user->getEmployerAddress(), $user->getEmployerMail()));
		
		if ($result) {
			return $this->getUserByUUID($uuid);
		}
		return false;
	}
	
	protected function updateUser($user){
		$emailLower = strtolower($user->getEmail());
				
		$statement = $this->db->prepare("UPDATE user 
			SET firstname = ?, lastname = ?, email = ?, engine = ?, employer_address = ?, employer_mail = ? WHERE uuid= ?");
		
		$result = $statement->execute(array($user->getFirstname(), $user->getLastname(), $emailLower, $user->getEngine()->getUuid(),
				$user->getEmployerAddress(), $user->getEmployerMail(), $user->getUuid()));
		
		if ($result) {
			return $this->getUserByUUID($user->getUuid());
		}
		return false;
	}
	
	protected function resultToObject($result){
		$object = new User();
		$object->setUuid($result['uuid']);
		$object->setEmail($result['email']);
		$object->setPassword($result['password']);
		$object->setFirstname($result['firstname']);
		$object->setLastname($result['lastname']);
		$object->setLocked($result['locked']);
		$object->setDeleted($result['deleted']);
		$object->setEmployerAddress($result['employer_address']);
		$object->setEmployerMail($result['employer_mail']);
		$object->setEngine($this->engineDAO->getEngine($result['engine']));
		$object->setPrivileges($this->privilegeDAO->getPrivilegesByUser($result['uuid']));
		return $object;
	}
	
	protected function createTableUser() {
		$statement = $this->db->prepare("CREATE TABLE user (
                          uuid CHARACTER(36) NOT NULL,
                          email VARCHAR(128) NOT NULL UNIQUE,
                          password VARCHAR(255),
						  firstname VARCHAR(64) NOT NULL,
                          lastname VARCHAR(64) NOT NULL,
						  engine CHARACTER(36) NOT NULL,
						  locked BOOLEAN NOT NULL,
						  deleted BOOLEAN NOT NULL,
						  employer_address VARCHAR(255),
						  employer_mail VARCHAR(255),
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			return true;
		}
		return false;
	}

}
	

?>