<?php 

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class UserController extends BaseController{
	
	protected $userDAO;
	protected $privilegeDAO;
	protected $tokenDAO;
	
	function __construct(PrivilegeDAO $privilegeDAO, UserDAO $userDAO, TokenDAO $tokenDAO) {
		parent::__construct();
		$this->userDAO = $userDAO;
		$this->privilegeDAO = $privilegeDAO;
		$this->tokenDAO = $tokenDAO;
	}
	
	function getUserDAO(){
		return $this->userDAO;
	}
		
	function getCurrentUser(){
		if( userLoggedIn()){
			return $this->userDAO->getUserByUUID(getCurrentUserUUID());
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
		if( ! $this->getCurrentUser()){
			return false;
		}
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
	
	function forgotPassword(String $email){
		
		$user = $this->userDAO->getUserByEmail($email);
		if($user){
			$token = new Token();
			$token->initialize(Token::ResetPassword, $user);
			$token = $this->tokenDAO->save($token);
			return $token;
		}
		return false;
	}
	
	function resetPassword(String $uuid){
		$password = $this->randomPassword();
		$pwhash = $this->hashPassword( $password );
		
		$user = $this->userDAO->getUserByUUID($uuid);
		if($user){
			$user->setPassword($pwhash);
			if($this->userDAO->save($user)){
				return $password;
			}
		}
		return false;
	}
	
	function changePassword(String $uuid, String $oldPassword, String $newPassword){
		$user = $this->userDAO->getUserByUUID($uuid);
		
		if( $this->checkPassword($user, $oldPassword) ){
			
			$pwhash = $this->hashPassword ( $newPassword );
			$user->setPassword($pwhash);
			$this->userDAO->save($user);
			return true;
		}
		return false;
	}
	
	function resetPasswordWithToken(Token $token, String $password){
		if( ! $token->isValid()){
			return false;
		}
		
		$user = $token->getUser();
		$pwhash = $this->hashPassword ( $password );
		$user->setPassword($pwhash);
		$user = $this->userDAO->save($user);
		if($user){
			$this->tokenDAO->deleteToken($token->getUuid());
			return true;
		}
		return false;
	}

	function addPrivilegeForMainEngineToUserByName(String $userUUID, String $privilege){
		$user = $this->userDAO->getUserByUUID($userUUID);
		$privilege = $this->privilegeDAO->getPrivilegeByName($privilege);
		$user->addPrivilegeForEngine($user->getEngine(), $privilege);
		return $this->userDAO->save($user);
	}
	
	function resetPrivilegesFromUser(String $userUUID, array $newPrivileges){
		$user = $this->userDAO->getUserByUUID($userUUID);
		$user->resetPrivileges($newPrivileges);
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
	
	function addDefaultPrivilegesToUser($user, $includeEventParticipant = true){
		$privileges = $this->privilegeDAO->getPrivileges();
		foreach($privileges as $privilege){
			if($privilege->getIsDefault()){
				if(! $includeEventParticipant && $privilege->getPrivilege() == Privilege::EVENTPARTICIPENT ){
					continue;
				}
				$user->addPrivilege($privilege);
			}
		}
		return $user;
	}
	
	function addDefaultPrivilegesToEventParticipant(User $user){
		$ep = $user->hasPrivilegeByName(Privilege::EVENTPARTICIPENT);
		return $this->addDefaultPrivilegesToUser($user, $ep);
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
		
		$userByMail->setPassword($this->hashPassword($user->getPassword()));

		//Add default privileges
		$this->addDefaultPrivilegesToUser($userByMail);
		
		//Save and return user
		return $this->userDAO->save($userByMail);
	}

	function checkPassword($user, $password){
		if ($password == $user->getPassword() ) {
			return $user->getUuid();
		}
		if (password_verify ( $password, $user->getPassword() )) {
			return $user->getUuid();
		}
		return false;		
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