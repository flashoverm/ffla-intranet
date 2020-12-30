<?php 

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class UserController extends BaseController{
	
	protected $userDAO;
	protected $privilegeDAO;
	
	function __construct(PrivilegeDAO $privilegeDAO, UserDAO $userDAO) {
		parent::__construct();
		$this->userDAO = $userDAO;
		$this->privilegeDAO = $privilegeDAO;
	}
	
	function getUserDAO(){
		return $this->userDAO;
	}
		
	function getCurrentUser(){
		if(isset($_SESSION ['intranet_userid'])){
			return $this->userDAO->getUserByUUID($_SESSION ['intranet_userid']);
		}
		return false;
	}
	
	function isEmailInUse(String $email){
		if( $this->userDAO->getUserByEmail($email) ){
			return true;
		}
		return false;
	}
	
	function hasCurrentUserPrivilege($privilegeName){
		return $this->getCurrentUser()->hasPrivilegeByName($privilegeName);
	}
	
	function lockUser(String $uuid){
		$user = $this->userDAO->getUserByUUID($uuid);
		$user->setLocked(true);
		return $this->userDAO->save($user);
	}
	
	function unlockUser(String $uuid){
		$user = $this->userDAO->getUserByUUID($uuid);
		$user->setLocked(false);
		return $this->userDAO->save($user);
	}
	
	function resetPassword(String $uuid){
		$password = random_password ();
		$pwhash = password_hash ( $password, PASSWORD_DEFAULT );
		
		$user = $this->userDAO->getUserByUUID($uuid);
		$user->setPassword($pwhash);
		return $this->userDAO->save($user);
	}
	
	function changePassword(String $uuid, String $oldPassword, String $newPassword){
		$user = $this->userDAO->getUserByUUID($uuid);
		
		if( password_verify($oldPassword, $user->getPassword()) || $oldPassword == $user->getPassword() ){
			
			$pwhash = password_hash ( $newPassword, PASSWORD_DEFAULT );
			$user->setPassword($pwhash);
			$this->userDAO->save($user);
			return true;
		}
		return false;
	}
	
	function addPrivilegeToUser(String $userUUID, String $privilegeUUID){
		$user = $this->userDAO->getUserByUUID($userUUID);
		$privilege = $this->privilegeDAO->getPrivilege($privilegeUUID);
		$user->addPrivilege($privilege);
		return $this->userDAO->save($user);
	}
	
	function addPrivilegeToUserByName(String $userUUID, String $privilege){
		$user = $this->userDAO->getUserByUUID($userUUID);
		$privilege = $this->privilegeDAO->getPrivilegeByName($privilege);
		$user->addPrivilege($privilege);
		return $this->userDAO->save($user);
	}
	
	function resetPrivilegesFromUser(String $userUUID, array $newPrivileges){
		$user = $this->userDAO->getUserByUUID($userUUID);
		$user->resetPrivileges($newPrivileges);
		return $this->userDAO->save($user);
	}
	
	function removePrivilegeFromUser(String $userUUID, String $privilegeUUID){
		$user = $this->userDAO->getUserByUUID($userUUID);
		$privilege = $this->privilegeDAO->getPrivilege($privilegeUUID);
		$user->removePrivilege($privilege);
		return $this->userDAO->save($user);
	}
	
	function deleteUser(String $uuid){
		$user = $this->userDAO->getUserByUUID($uuid);
		$user->setDeleted(true);
		$this->userDAO->save($user);
		return true;
	}
	
	function undeleteUser(String $uuid){
		$user = $this->userDAO->getUserByUUID($uuid);
		$user->setDeleted(false);
		return $this->userDAO->save($user);
	}
	
	function addDefaultPrivilegesToUser($user){
		$privileges = $this->privilegeDAO->getPrivileges();
		foreach($privileges as $privilege){
			if($privilege->getIsDefault()){
				$user->addPrivilege($privilege);
			}
		}
		return $user;
	}
	
	function createNewUser(User $user){
		$userByMail = $this->userDAO->getUserByEmail($user->getEmail());
		if( ! $userByMail){
			//Create complete new user with default privileges
			
			//Hash given password
			$user->setPassword($this->hashPassword($user->getPassword()));
			
			//Add default privileges
			$this->addDefaultPrivilegesToUser($user);
			
			//Save and return user
			return $this->userDAO->save($user);
		}

		if( ! $this->compareUserByData($user, $userByMail)){
			//Data does not match with existing user of this mail
			throw new Exception("User data mismatch", 101);
		}
		
		if($userByMail->getPassword() != NULL){
			//Already existing user including password!
			throw new Exception("User with login existing", 102);
		}
		
		//Add given password or generate new
		if($user->getPassword() != NULL){
			$userByMail->setPassword($this->hashPassword($user->getPassword()));
		} else {
			$userByMail->setPassword($this->createPassword());
		}
		
		//Add default privileges
		$this->addDefaultPrivilegesToUser($userByMail);
		
		//Save and return user
		return $this->userDAO->save($userByMail);
	}
	
	protected function createPassword(){
		$password = randomPassword ();
		return hashPassword($password);
	}
	
	protected function hashPassword($password){
		$pwhash = password_hash ( $password, PASSWORD_DEFAULT );
		return $pwhash;
	}
	
	protected function randomPassword($length = 8) {
		// $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password = substr ( str_shuffle ( $chars ), 0, $length );
		return $password;
	}
	
	protected function compareUserByData(User $user, User $otherUser){
		if(! $user->getEmail() == $otherUser->getEmail()){
			return false;
		}
		if(! $user->getFirstname() == $otherUser->getFirstname()){
			return false;
		}
		if(! $user->getLastname() == $otherUser->getLastname()){
			return false;
		}
		if(! $user->getEngine()->getUuid() == $otherUser->getEngine()->getUuid()){
			return false;
		}
		return true;
	}

}
	
?>