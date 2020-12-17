<?php 

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class UserController extends BaseController{
	
	protected $userDAO;
	protected $privilegeDAO;
	
	function __construct() {
		$this->userDAO = new UserDAO();
		$this->privilegeDAO = new PrivilegeDAO();
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
		$this->userDAO->save($user);
	}
	
	function unlockUser(String $uuid){
		$user = $this->userDAO->getUserByUUID($uuid);
		$user->setLocked(false);
		$this->userDAO->save($user);
	}
	
	function resetPassword(String $uuid){
		$password = random_password ();
		$pwhash = password_hash ( $password, PASSWORD_DEFAULT );
		
		$user = $this->userDAO->getUserByUUID($uuid);
		$user->setPassword($pwhash);
		$this->userDAO->save($user);
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
		$this->userDAO->save($user);
	}
	
	function addPrivilegeToUserByName(String $userUUID, String $privilege){
		$user = $this->userDAO->getUserByUUID($userUUID);
		$privilege = $this->privilegeDAO->getPrivilegeByName($privilege);
		$user->addPrivilege($privilege);
		$this->userDAO->save($user);
	}
	
	function resetPrivilegesFromUser(String $userUUID, array $newPrivileges){
		$user = $this->userDAO->getUserByUUID($userUUID);
		$user->resetPrivileges($newPrivileges);
		$this->userDAO->save($user);
	}
	
	function removePrivilegeFromUser(String $userUUID, String $privilegeUUID){
		$user = $this->userDAO->getUserByUUID($userUUID);
		$privilege = $this->privilegeDAO->getPrivilege($privilegeUUID);
		$user->removePrivilege($privilege);
		$this->userDAO->save($user);
	}
	
	function deleteUser(String $uuid){
		$user = $this->userDAO->getUserByUUID($uuid);
		$user->setDeleted(true);
		$this->userDAO->save($user);
	}
	
	function undeleteUser(String $uuid){
		$user = $this->userDAO->getUserByUUID($uuid);
		$user->setDeleted(false);
		$this->userDAO->save($user);
	}
}
	
?>