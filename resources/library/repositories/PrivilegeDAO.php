<?php 

require_once "BaseDAO.php";

use Doctrine\ORM\EntityManager;

class PrivilegeDAO extends BaseDAO{
	
	protected $privilegeRepository;
	
	function __construct(EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->privilegeRepository = $entityManager->getRepository('Privilege');
	}
	
	static function initializePrivileges(){
		//TODO change to objects -> persist
		insert_privilege('5873791F-68EC-159D-EF91-3288F02EF1D2', FILEADMIN);
		insert_privilege('10590E6B-FC09-49B3-6A35-53759D10D1FC', FFADMINISTRATION);
		insert_privilege('6B296269-6280-EAC5-B5F3-4A95C3FA7656', ENGINEHYDRANTMANANGER);
		insert_privilege('2B3DE880-1EB7-C9A1-C533-BD90F773FDBA', HYDRANTADMINISTRATOR);
		insert_privilege('EE50BFB0-B4B0-2AE2-AAE4-2FB6EE9DA558', PORTALADMIN);
		insert_privilege('231C64FA-24F4-CDA4-60FE-B211A364D5AE', EDITUSER, true);
		insert_privilege('C4E19AFC-14CA-9714-B0E6-B1354EC0571C', EVENTPARTICIPENT, true);
		insert_privilege('26F7145B-826A-F731-4F59-E435B2E94F81', EVENTMANAGER);
		insert_privilege('9941EE1E-6E61-0656-E72B-18A4EE48633C', EVENTADMIN);
	}
	
	function getPrivileges(){
		return $this->privilegeRepository->findAll();
	}
	
	/*
	 * "SELECT user.*
		FROM user, user_privilege 
		WHERE uuid = user_privilege.user AND privilege = ?
		AND user.deleted = false"
	 */
	function getUsersWithPrivilege(String $uuid){
		$privilege = $this->privilegeRepository->findBy($uuid);
		
		return PrivilegeDAO::filterDeletedUsers($privilege->getUsers());
	}
	
	/*
	 * "SELECT user.* 
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND privilege.uuid = user_privilege.privilege 
		AND privilege.privilege = ?
		AND user.deleted = false"
	 */
	function getUsersWithPrivilegeByName(String $privilegeName){
		$criteria = array('privilege' => $privilegeName);
		$privilege = $this->privilegeRepository->findBy($criteria);
		
		return PrivilegeDAO::filterDeletedUsers($privilege->getUsers());
	}
	
	function getPrivilege(String $uuid){
		return $this->privilegeRepository->findByID($uuid);
	}
	
	function getPrivilegeByName(String $name){
		$criteria = array('privilege' => $name);
		return $this->privilegeRepository->findBy($criteria);
	}
		
	protected static function filterDeletedUsers($users){
		
		return array_filter($users, array(__CLASS__, 'isNotDeleted'));
	}
	
	protected static function isNotDeleted($user){
		return ! $user->getDeleted();
	}
	
}