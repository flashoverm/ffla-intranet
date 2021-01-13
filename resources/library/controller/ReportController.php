<?php 

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class ReportController extends BaseController{
	
	protected $reportDAO;
	
	function __construct(ReportDAO $reportDAO) {
		parent::__construct();
		$this->reportDAO = $reportDAO;
	}

	function setEmsEntry($reportUuid){
		$report = $this->reportDAO->getReport($reportUuid);
		$report->setEmsEntry(true);
		return $this->reportDAO->updateReportOnly($report);
	}
	
	function unsetEmsEntry($reportUuid){
		$report = $this->reportDAO->getReport($reportUuid);
		$report->setEmsEntry(false);
		return $this->reportDAO->updateReportOnly($report);
	}
	
	function setApproval($reportUuid){
		$report = $this->reportDAO->getReport($reportUuid);
		$report->setManagerApproved(true);
		return $this->reportDAO->updateReportOnly($report);
	}
	
	function unsetApproval($reportUuid){
		$report = $this->reportDAO->getReport($reportUuid);
		$report->setManagerApproved(false);
		return $this->reportDAO->updateReportOnly($report);
	}
	
}