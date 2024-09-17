<?php

require_once "BaseDAO.php";

class UserDAO extends BaseDAO {
	
	protected $userPrivilegeDAO;
	protected $engineDAO;
	
	public function __construct(PDO $pdo, UserPrivilegeDAO $userPrivilegeDAO, EngineDAO $engineDAO) {
		parent::__construct($pdo, "user");
		$this->engineDAO = $engineDAO;
		$this->userPrivilegeDAO = $userPrivilegeDAO;
	}
	
	public function save(User $user){
		$saved = null;
		if($this->uuidExists($user->getUuid(), $this->tableName)){
			$saved = $this->updateUser($user);
		} else {
			$saved = $this->insertUser($user);
		}
		if($saved != null){
			$privilegeSaved = $this->userPrivilegeDAO->saveUsersPrivilege($user);
			if( $privilegeSaved){
				$enginesSaved = $this->saveAdditionalEngines($user);
				if($enginesSaved){
				    $settingsSaved = $this->saveSettings($user);
				    if($settingsSaved){
				        return $this->getUserByUUID($saved->getUuid());
				    }
				}
			}
		}
		return false;
	}
		
	public function getUsers(){
		$statement = $this->db->prepare("SELECT * FROM user WHERE deleted = false ORDER BY email");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	public function getUnlockedUsers(){
		$statement = $this->db->prepare("SELECT * FROM user
		WHERE locked = FALSE
		AND deleted = false
		ORDER BY lastname");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	public function getDeletedUsers(){
		$statement = $this->db->prepare("SELECT * FROM user WHERE deleted = true ORDER BY email");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	public function getUsersByEngine(String $engineUUID){
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
	
	
	public function getUsersWithPrivilege(String $uuid){
		$statement = $this->db->prepare("SELECT user.*
		FROM user, user_privilege
		WHERE uuid = user_privilege.user AND privilege = ?
		AND user.deleted = false
        GROUP BY user.uuid");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	public function getUsersWithPrivilegeByName(String $name){
		$statement = $this->db->prepare("SELECT user.*
		FROM user, user_privilege
		WHERE user.uuid = user_privilege.user
        AND user_privilege.privilege = ?
		AND user.deleted = false
        GROUP BY user.uuid");
		
		if ($statement->execute(array($name))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	public function getUsersTypeaheadWithPrivilegeByName(String $query, String $privilegeName){
	    $queryString = "%".strtolower($query)."%";
	    $statement = $this->db->prepare("SELECT user.*
		FROM user, user_privilege
		WHERE user.uuid = user_privilege.user
        AND user_privilege.privilege = ?
		AND user.deleted = false
        AND ( LOWER(user.firstname) LIKE ? OR LOWER(user.lastname) LIKE ? OR LOWER(CONCAT(user.firstname, ' ', user.lastname)) LIKE ?)
        GROUP BY user.uuid");
	    
	    if ($statement->execute(array($privilegeName, $queryString, $queryString, $queryString))) {
	        return $this->handleResult($statement, true);
	    }
	    return false;
	}
	
	public function getUsersByEngineAndPrivilege(String $engineUuid, String $privilegeName){
		$statement = $this->db->prepare("SELECT user.*
		FROM user, user_privilege
		WHERE user.uuid = user_privilege.user
        AND user_privilege.privilege = ?
		AND user_privilege.engine = ?
		AND user.deleted = false
		ORDER BY user.lastname");
	
		if ($statement->execute(array($privilegeName, $engineUuid))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	public function getUsersByAllEngines(String $engineUuid){
	    $statement = $this->db->prepare("SELECT user.*
		FROM user
        WHERE (user.engine = ? OR user.uuid IN 
            (SELECT additional_engines.user FROM additional_engines WHERE engine = ? ))
		AND user.deleted = false
		ORDER BY user.lastname");
	    	    
	    if ($statement->execute(array($engineUuid, $engineUuid))) {
	        return $this->handleResult($statement, true);
	    }
	    return false;
	}
	
	public function getUsersWithPrivilegeByAllEngines(String $engineUuid, String $privilegeName){
	    $statement = $this->db->prepare("SELECT user.*
		FROM user, user_privilege
        WHERE user.uuid = user_privilege.user
        AND user_privilege.privilege = ?
        AND user_privilege.engine = ?
		AND user.deleted = false
		ORDER BY user.lastname");
	    
	    if ($statement->execute(array($privilegeName, $engineUuid))) {
	        return $this->handleResult($statement, true);
	    }
	    return false;
	}
	
	public function getUsersByAllEnginesAndPrivilege(String $engineUuid, String $userUuid, String $privilegeName){
	    $statement = $this->db->prepare("SELECT user.*
		FROM user, user_privilege
		WHERE user.uuid = user_privilege.user
        AND user_privilege.privilege = ?
        AND (user.engine = ?
            OR user.engine IN (SELECT additional_engines.engine FROM additional_engines WHERE user = ? )
            OR user_privilege.engine = ?
        )
		AND user.deleted = false
		ORDER BY user.lastname");
	    	    
	    if ($statement->execute(array($privilegeName, $engineUuid, $userUuid, $engineUuid))) {
	        return $this->handleResult($statement, true);
	    }
	    return false;
	}
	
	public function getUserByUUID(String $uuid){
		$statement = $this->db->prepare("SELECT * FROM user WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	public function getUserByEmail(String $email){
		$emailLower = strtolower($email);
		
		$statement = $this->db->prepare("SELECT * FROM user WHERE email = ?");
		
		if ($statement->execute(array($emailLower))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	public function getUserByData(String $firstname, String $lastname, String $email, String $engineUUID){
		$statement = $this->db->prepare("SELECT * FROM user WHERE firstname = ? AND lastname = ? AND email = ? AND engine = ? ");
		
		if ($statement->execute(array($firstname, $lastname, $email, $engineUUID))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	public function getDuplicateUsersEmail(){
		$statement = $this->db->prepare("SELECT email FROM user GROUP BY email HAVING COUNT(*) > 1");
		
		if ($statement->execute()) {
			return $statement->fetchAll();
		}
		return false;
	}
	
	public function deleteUser($uuid){
		$statement = $this->db->prepare("DELETE FROM user_privilege WHERE user= ?");
		
		if ($statement->execute(array($uuid))) {
			$statement = $this->db->prepare("DELETE FROM user WHERE uuid= ?");
			
			if ($statement->execute(array($uuid))) {
				return true;
			}
			return false;
		}
		return false;
	}
	
	/*
	 * Init and helper methods
	 */
	
	protected function filterDeletedUsers($users){
		return array_filter($users, array(__CLASS__, 'isNotDeleted'));
	}
	
	protected function isNotDeleted($user){
		return ! $user->getDeleted();
	}
	
	protected function insertUser(User $user){
		$uuid = $this->generateUuid();
		$user->setUuid($uuid);
		$emailLower = strtolower($user->getEmail());
		
		$statement = $this->db->prepare("INSERT INTO user (
			uuid, email, firstname, lastname, password, engine, locked, employer_address, employer_mail
			) VALUES (?, ?, ?, ?, ?, ?, FALSE, ?, ?)");
		
		$result = $statement->execute(array($uuid, $emailLower, $user->getFirstname(), $user->getLastname(),
				$user->getPassword(), $user->getEngine()->getUuid(), $user->getEmployerAddress(),
				$user->getEmployerMail()
		));
		
		if ($result) {
			return $this->getUserByUUID($uuid);
		}
		return false;
	}
	
	protected function updateUser(User $user){
		$emailLower = strtolower($user->getEmail());
				
		$statement = $this->db->prepare("UPDATE user
			SET firstname = ?, lastname = ?, email = ?, password = ?, engine = ?, locked = ?, deleted = ?, last_login = ?, employer_address = ?, employer_mail = ? WHERE uuid= ?");
		
		$result = $statement->execute(array($user->getFirstname(), $user->getLastname(), $emailLower,
				$user->getPassword(), $user->getEngine()->getUuid(), $user->getLocked(), $user->getDeleted(), $user->getLastLogin(),
				$user->getEmployerAddress(), $user->getEmployerMail(), $user->getUuid()
		));
		
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
		$object->setLastLogin($result['last_login']);
		$object->setEmployerAddress($result['employer_address']);
		$object->setEmployerMail($result['employer_mail']);
		$object->setEngine($this->engineDAO->getEngine($result['engine']));
		$object->setPrivileges($this->userPrivilegeDAO->getPrivilegesByUser($result['uuid']));
		$object->setAdditionalEngines($this->getAdditionalEngines($result['uuid']));
		$object->setSettings($this->getSettings($result['uuid']));
		return $object;
	}
	
	protected function resultToAdditionalEngines($result){
		return $this->engineDAO->getEngine($result['engine']);
	}

	protected function getAdditionalEngines($userUuid){
		$statement = $this->db->prepare("SELECT * FROM additional_engines WHERE user = ?");
		
		if ($statement->execute(array($userUuid))) {
			return $this->handleResult($statement, true, "resultToAdditionalEngines");
		}
		return false;
	}
	
	protected function saveAdditionalEngines(User $user){
		$statement = $this->db->prepare("DELETE FROM additional_engines WHERE user = ?");
		
		$result = $statement->execute(array($user->getUuid()));
		
		if ($result) {
			foreach($user->getAdditionalEngines() as $additionalEngine){
				$this->insertAdditionalEngine($user->getUuid(), $additionalEngine->getUuid());
			}
			return true;
		}
		return false;
	}
	
	protected function insertAdditionalEngine($userUuid, $engineUuid){
		$statement = $this->db->prepare("INSERT INTO additional_engines (user, engine) VALUES (?, ?)");
		
		$result = $statement->execute(array($userUuid, $engineUuid));
		
		if ($result) {
			return true;
		}
		return false;
	}
	
	protected function resultToSettings($result){
	    return SettingDAO::getSetting( $result['setting']);
	}
	
	protected function getSettings($userUuid){
	    $statement = $this->db->prepare("SELECT * FROM user_setting WHERE user = ?");
	    
	    if ($statement->execute(array($userUuid))) {
	        return $this->handleResult($statement, true, "resultToSettings");
	    }
	    return false;
	}
	
	protected function saveSettings(User $user){
	    $statement = $this->db->prepare("DELETE FROM user_setting WHERE user = ?");
	    
	    $result = $statement->execute(array($user->getUuid()));
	    
	    if ($result) {
	        foreach($user->getSettings() as $usersSetting){
	            $this->insertIntoSettings($user->getUuid(), $usersSetting->getKey());
	        }
	        return true;
	    }
	    return false;
	}
	
	protected function insertIntoSettings($userUuid, $settingKey){
	    $statement = $this->db->prepare("INSERT INTO user_setting (user, setting) VALUES (?, ?)");
	    
	    $result = $statement->execute(array($userUuid, $settingKey));
	    
	    if ($result) {
	        return true;
	    }
	    return false;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE user (
                          uuid CHARACTER(36) NOT NULL,
                          email VARCHAR(128) NOT NULL UNIQUE,
                          password VARCHAR(255),
						  firstname VARCHAR(64) NOT NULL,
                          lastname VARCHAR(64) NOT NULL,
						  engine CHARACTER(36) NOT NULL,
						  locked BOOLEAN NOT NULL default 0,
						  deleted BOOLEAN NOT NULL default 0,
						  last_login DATETIME NULL,
						  employer_address VARCHAR(255),
						  employer_mail VARCHAR(255),
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			$statement = $this->db->prepare("CREATE TABLE additional_engines (
                          user CHARACTER(36) NOT NULL,
                          engine CHARACTER(36) NOT NULL,
                          PRIMARY KEY  (user, engine),
						  FOREIGN KEY (user) REFERENCES user(uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
			
			$result = $statement->execute();
			if ($result) {
			    $statement = $this->db->prepare("CREATE TABLE user_setting (
                          user CHARACTER(36) NOT NULL,
                          setting VARCHAR(128) NOT NULL,
                          PRIMARY KEY  (user, setting),
						  FOREIGN KEY (user) REFERENCES user(uuid)
                          )");
			    
			    $result = $statement->execute();
			    if ($result) {
			        return true;
			    }
			}
		}
		return false;
	}

}
