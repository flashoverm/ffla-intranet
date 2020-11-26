<?php 

require_once "BaseDAO.php";

use Doctrine\ORM\EntityManager;

class UserDAO extends BaseDAO {
	
	protected $userRepository;
	
	function __construct(EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->userRepository = $entityManager->getRepository('User');
	}
		
	function getUsers(){
		return $this->userRepository->findBy(array('deleted' => 'false'));
	}
	
	function getUnlockedUsers(){
		$criteria = array('locked' => 'false', 'deleted' => 'false');
		return $this->userRepository->findBy($criteria);
	}
	
	function getDeletedUsers(){
		$criteria = array('deleted' => 'true');
		return $this->userRepository->findBy($criteria);
	}
	
	function getUsersByEngine(String $engineUUID){
		$criteria = array('engine' => $engineUUID, 'locked' => 'false', 'deleted' => 'false');
		return $this->userRepository->findBy($criteria);
	}
		
	function getUserByUUID(String $uuid){
		return $this->userRepository->findByID($uuid);
	}
	
	function getUserByEmail(String $email){
		$criteria = array('email' => $email);
		return $this->userRepository->findBy($criteria);
	}
	
	function getUserByData(String $firstname, String $lastname, String $email, String $engineUUID){
		$criteria = array(	'firstname' => $firstname, 
							'lastname' => $lastname, 
							'email' => $email, 
							'engine' => $engineUUID);
		return $this->userRepository->findBy($criteria);
	}

}
	

?>