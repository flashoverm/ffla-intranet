<?php

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class EventController extends BaseController{
	
	protected $eventDAO;
	protected $userDAO;
	protected $userController;
	
	function __construct() {
		$this->eventDAO = new EventDAO();
		$this->userDAO = new UserDAO();
		$this->userController = new UserController();
	}
	
	function saveEvent(Event $event, $deletedStaffUuids){
		//new event - just save
		if(! $event->getUuid()){
			return $this->eventDAO->save($event);
		}
		
		//inform deleted user and delete entries
		foreach($deletedStaffUuids as $deletedStaffUuid){
			echo $deletedStaffUuid . "<br>";
			$staff = $this->eventDAO->getEventStaffEntry($deletedStaffUuid);
			if($staff->getUser() != NULL){
				mail_remove_staff_user($staff->getUuid(), $event->getUuid());
			}
			if( ! $this->eventDAO->deleteEventStaffEntry($staff->getUuid())){
				return false;
			}
		}
		
		var_dump($event->getStaff());
		
		$saved = $this->eventDAO->save($event);
		
		echo "<br><br>";
		var_dump($saved->getStaff());
		
		return $saved;
	}
		
	function isUserManagerOrCreator($userUuid, $eventUuid){
		$event = $this->eventDAO->getEvent($eventUuid);
		if($event->getCreator()->getUuid() == $userUuid){
			return true;
		}
		$user = $this->userDAO->getUserByUUID($userUuid);
		if($user->hasPrivilegeByName(Privilege::EVENTMANAGER)
				&& $event->getEngine()->getUuid() == $user->getEngine()->getUuid()){
			return true;		
		}
		return false;
	}
	
	function publishEvent($eventUuid){
		$event = $this->eventDAO->getEvent($eventUuid);
		$event->setPublished(true);
		return $this->eventDAO->save($event);
	}
	
	function markAsDeleted($eventUuid){
		$event = $this->eventDAO->getEvent($eventUuid);
		$event->setDeletedBy($this->userController->getCurrentUser());
		return $this->eventDAO->save($event);
	}
	
	function confirmStaffUser($staffUuid){
		$staff = $this->eventDAO->getEventStaffEntry($staffUuid);
		$staff->setUnconfirmed(false);
		return $this->eventDAO->updateEventStaffEntry($staff);
	}
	
	function subscribeUser($staffUuid, User $user){
		$staff = $this->eventDAO->getEventStaffEntry($staffUuid);
		if($staff->getUser() == NULL){
			$staff->setUser($user);
			return $this->eventDAO->updateEventStaffEntry($staff);
		}
		return -1;
	}
	
	function assignUser($staffUuid, User $user){
		$staff = $this->eventDAO->getEventStaffEntry($staffUuid);
		$staff->setUnconfirmed(false);
		$staff->setUser($user);
		return $this->eventDAO->updateEventStaffEntry($staff);
	}
	
	function removeUser($staffUuid){
		$staff = $this->eventDAO->getEventStaffEntry($staffUuid);
		$staff->setUnconfirmed(true);
		$staff->setUser(NULL);
		return $this->eventDAO->updateEventStaffEntry($staff);
	}
}