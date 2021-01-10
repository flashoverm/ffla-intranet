<?php

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class DataChangeRequestController extends BaseController{
	
	protected $dataChangeRequestDAO;
	
	function __construct(DataChangeRequestDAO $dataChangeRequestDAO) {
		parent::__construct();
		$this->dataChangeRequestDAO = $dataChangeRequestDAO;
	}
	
	function dataChangeRequestDone($uuid, $advisor){
		$dataChangeRequest = $this->dataChangeRequestDAO->getDataChangeRequest($uuid);
		$dataChangeRequest->setState(DataChangeRequest::DONE);
		$dataChangeRequest->setLastAdvisor($advisor);
		return $this->dataChangeRequestDAO->save($dataChangeRequest);
	}
	
	function declineDataChangeRequest($uuid, $advisor){
		$dataChangeRequest = $this->dataChangeRequestDAO->getDataChangeRequest($uuid);
		$dataChangeRequest->setState(DataChangeRequest::DECLINED);
		$dataChangeRequest->setLastAdvisor($advisor);
		return $this->dataChangeRequestDAO->save($dataChangeRequest);
	}
	
}