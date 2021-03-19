<?php 

require_once "BaseDAO.php";

class UserPrivilegeDAO extends BaseDAO {
	
	protected $privilegeDAO;
	protected $engineDAO;
	
	function __construct(PDO $pdo, PrivilegeDAO $privilegeDAO, EngineDAO $engineDAO) {
		parent::__construct($pdo, "user_privilege");
		$this->privilegeDAO = $privilegeDAO;
		$this->engineDAO = $engineDAO;
	}
	
	function saveUsersPrivilege(User $user){
		$statement = $this->db->prepare("DELETE FROM user_privilege WHERE user = ?");
		
		$result = $statement->execute(array($user->getUuid()));
		
		if ($result) {
			foreach($user->getPrivileges() as $userPrivilege){
				$this->insertUsersPrivilege(
						$userPrivilege->getPrivilege()->getUuid(), 
						$user->getUuid(), 
						$userPrivilege->getEngine()->getUuid()
				);
			}
			
			return true;
			
		}
		return false;
	}
	
	function getPrivilegesByUser(String $userUuid){
		$statement = $this->db->prepare("SELECT user_privilege.* FROM user_privilege, privilege WHERE privilege.uuid = user_privilege.privilege AND user = ? ");
		
		if ($statement->execute(array($userUuid))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}

	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new UserPrivilege(
				$this->engineDAO->getEngine($result['engine']), 
				$this->privilegeDAO->getPrivilege($result['privilege']), 
				$result['user']
		);
		return $object;
	}
	
	protected function insertUsersPrivilege($privilegeUuid, $userUuid, $engineUuid){
		$statement = $this->db->prepare("INSERT INTO user_privilege (user, engine, privilege) VALUES (?, ?, ?)");
		
		$result = $statement->execute(array($userUuid, $engineUuid, $privilegeUuid));
		
		if ($result) {
			return true;
		}
		return false;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE user_privilege (
							  privilege CHARACTER(36) NOT NULL,
							  engine CHARACTER(36) NOT NULL,
							  user CHARACTER(36) NOT NULL,
	                          PRIMARY KEY (privilege, engine, user),
							  FOREIGN KEY (privilege) REFERENCES privilege(uuid),
							  FOREIGN KEY (engine) REFERENCES engine(uuid),
							  FOREIGN KEY (user) REFERENCES user(uuid)
	                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			return true;
		}
		return false;
	}
	
}