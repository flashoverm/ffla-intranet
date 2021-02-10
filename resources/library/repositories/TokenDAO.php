<?php

require_once "BaseDAO.php";

class TokenDAO extends BaseDAO{
	
	protected $userDAO;
	
	function __construct(PDO $pdo, UserDAO $userDAO) {
		parent::__construct($pdo, "token");
		$this->userDAO = $userDAO;
		
		$this->clearInvalidTokens();
	}
	
	function save(Token $token){
		$uuid = $this->generateUuid();
		$token->setUuid($uuid);
		
		$statement = $this->db->prepare("INSERT INTO token (
			uuid, type, token, valid_until, user)
			VALUES (?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($token->getUuid(), $token->getType(), 
				$token->getToken(), $token->getValidUntil(), $token->getUser()->getUuid()));
		
		if ($result) {
			return $token;
		} else {
			return false;
		}
	}
	
	function getTokenByToken(string $token){
		$statement = $this->db->prepare("SELECT * FROM token WHERE token = ?");
		
		if ($statement->execute(array($token))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function deleteToken(string $tokenUuid){
		$statement = $this->db->prepare("DELETE FROM token WHERE uuid = ?");
		
		if ($statement->execute(array($tokenUuid))) {
			return true;
		}
		return false;
	}
	
	function clearInvalidTokens(){
		$statement = $this->db->prepare("DELETE FROM token WHERE valid_until < (CURDATE() - INTERVAL 30 DAY)");
		
		if ($statement->execute()) {
			return true;
		}
		return false;
	}
	
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new Token();
		$object->setUuid($result['uuid']);
		$object->setType($result['type']);
		$object->setToken($result['token']);
		$object->setValidUntil($result['valid_until']);
		$object->setUser($this->userDAO->getUserByUUID($result['user']));
		return $object;
	}

	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE token (
						  uuid CHARACTER(36) NOT NULL,
						  type TINYINT NOT NULL,
						  token VARCHAR(255) NOT NULL,
                          valid_until DATETIME NOT NULL,
						  user CHARACTER(36) NOT NULL,
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