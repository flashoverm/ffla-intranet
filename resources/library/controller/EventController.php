<?php

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class EventController extends BaseController{
    
    public static function get_event_link($event_uuid){
        global $config;
        return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/view/" . $event_uuid;
    }
    
    public static function get_staff_acknowledge_link($event_uuid, $staff_uuid){
        global $config;
        return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/" . $event_uuid . "/acknowledge/" . $staff_uuid;
    }
    
    public static function event_subject($event_uuid){
        global $config, $eventDAO;
        $event = $eventDAO->getEvent($event_uuid);
        
        $subject = " - "
            . date($config ["formats"] ["date"], strtotime($event->getDate())) . " "
                . date($config ["formats"] ["time"], strtotime($event->getStartTime())) . " Uhr "
                    . $event->getType()->getType();
                    
                    return $subject;
    }
	
	protected $eventDAO;
	protected $staffDAO;
	protected $userDAO;
	protected $userController;
	
	function __construct(EventDAO $eventDAO, StaffDAO $staffDAO, UserDAO $userDAO, UserController $userController) {
		parent::__construct();
		$this->eventDAO = $eventDAO;
		$this->staffDAO = $staffDAO;
		$this->userDAO = $userDAO;
		$this->userController = $userController;
	}
	
	function saveEvent(Event $event, $deletedStaffUuids){
		//new event - just save
		if(! $event->getUuid()){
			return $this->eventDAO->save($event);
		}
		
		//inform deleted user and delete entries
		foreach($deletedStaffUuids as $deletedStaffUuid){
			$staff = $this->staffDAO->getEventStaffEntry($deletedStaffUuid);
			if($staff->getUser() != NULL){
				mail_remove_staff_user($staff->getUuid(), $event->getUuid());
			}
			if( ! $this->staffDAO->deleteEventStaffEntry($staff->getUuid())){
				return false;
			}
		}
		
		$saved = $this->eventDAO->save($event);
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
	
	function cancelEvent($eventUuid, $reason){
		$event = $this->eventDAO->getEvent($eventUuid);
		if($event->isCanceled()){
			return false;
		}
		$event->setCanceledBy($this->userController->getCurrentUser());
		$event->setCancelationReason($reason);
		return $this->eventDAO->save($event);
	}
	
	function acknowledgeStaffUser($staffUuid){
		$staff = $this->staffDAO->getEventStaffEntry($staffUuid);
		$staff->setUserAcknowledged(true);
		return $this->staffDAO->save($staff);
	}
	
	function confirmStaffUser($staffUuid){
		$staff = $this->staffDAO->getEventStaffEntry($staffUuid);
		$staff->setUnconfirmed(false);
		return $this->staffDAO->save($staff);
	}
	
	function subscribeUser($staffUuid, User $user){
		$staff = $this->staffDAO->getEventStaffEntry($staffUuid);
		if($staff->getUser() == NULL){
			$staff->setUser($user);
			$staff->setUserAcknowledged(true);
			$staff->setUnconfirmed(true);
			return $this->staffDAO->save($staff);
		}
		return -1;
	}
	
	function assignUser($staffUuid, User $user){
		$staff = $this->staffDAO->getEventStaffEntry($staffUuid);
		$staff->setUnconfirmed(false);
		$staff->setUser($user);
		return $this->staffDAO->save($staff);
	}
	
	function removeUser($staffUuid){
		$staff = $this->staffDAO->getEventStaffEntry($staffUuid);
		$staff->setUnconfirmed(true);
		$staff->setUserAcknowledged(false);
		$staff->setUser(NULL);
		return $this->staffDAO->save($staff);
	}
}