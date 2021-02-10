<?php 

require_once 'UserController.php';

//use Doctrine\ORM\EntityManager;

class GuardianUserController extends UserController{
	
	function __construct(PrivilegeDAO $privilegeDAO, UserDAO $userDAO, TokenDAO $tokenDAO) {
		parent::__construct($privilegeDAO, $userDAO, $tokenDAO);
	}
	
	public function insertEventParticipant($firstname, $lastname, $email, $engine){
		$user = new User();
		$user->setUserData($firstname, $lastname, $email, $engine, null, null);
		$user->addPrivilege($this->privilegeDAO->getPrivilegeByName(Privilege::EVENTPARTICIPENT));
		$this->userDAO->save($user);
	}
	
	public function getEventmanagerOfEngine($engineUuid){
		
		return $this->userDAO->getUsersByEngineAndPrivilege($engineUuid, Privilege::EVENTMANAGER);
	}
	
	public function getEventParticipantOfEngine($engineUuid){
		
		return $this->userDAO->getUsersByEngineAndPrivilege($engineUuid, Privilege::EVENTPARTICIPENT);
		
	}
	
	public function getEventManangerExeptEngineAndCreator($engineUuid, $creatorUuid){
		$users =$this->getEventmanagerOfEngine($engineUuid);
		$manager = array();
		
		foreach($users as $user){
			
			if($user->getUuid() != $creatorUuid
					&& $user->getEngine()->getUuid() == $engineUuid){
						$manager [] = $user;
			}
		}
		return $manager;
	}
		
	public function isUserAllowedToEditEvent(User $user, $eventUuid){
		global $eventDAO;
		
		if($user->hasPrivilegeByName(Privilege::EVENTADMIN)){
			return true;
		}
		
		if($user->hasPrivilegeByName(Privilege::FFADMINISTRATION)){
			return true;
		}
		
		$event = $eventDAO->getEvent($eventUuid);
		if($user->hasPrivilegeByName(Privilege::EVENTMANAGER) 
				&& $event->getEngine()->getUuid() == $user->getEngine()->getUuid()){
			return true;
		}
		return false;
	}
	
	public function isUserAllowedToEditReport($user, $reportUuid){
		global $reportDAO;
		
		if($user->hasPrivilegeByName(Privilege::EVENTADMIN)){
			return true;
		}
		
		if($user->hasPrivilegeByName(Privilege::FFADMINISTRATION)){
			return true;
		}
		
		$report = $reportDAO->getReport($reportUuid);
		if($user->hasPrivilegeByName(Privilege::EVENTMANAGER)
				&& $report->getEngine()->getUuid() == $user->getEngine()->getUuid()){
					return true;
		}
		return false;
	}
}