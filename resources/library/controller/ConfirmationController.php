<?php

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class ConfirmationController extends BaseController{
	
	protected $confirmationDAO;
	
	function __construct(ConfirmationDAO $confirmationDAO) {
		parent::__construct();
		$this->confirmationDAO = $confirmationDAO;
	}
	
	function acceptConfirmation($uuid, $advisor){
		$confirmation = $this->confirmationDAO->getConfirmation($uuid);
		$confirmation->setState(Confirmation::ACCEPTED);
		$confirmation->setLastAdvisor($advisor);
		return $this->confirmationDAO->save($confirmation);
	}
	
	function declineConfirmation($uuid, $reason, $advisor){
		$confirmation = $this->confirmationDAO->getConfirmation($uuid);
		$confirmation->setState(Confirmation::DECLINED);
		$confirmation->setLastAdvisor($advisor);
		$confirmation->setReason($reason);
		return $this->confirmationDAO->save($confirmation);
	}
	
}