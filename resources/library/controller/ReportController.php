<?php 

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class ReportController extends BaseController{
	
	protected $reportDAO;
	
	function __construct() {
		$this->reportDAO = new ReportDAO();
	}

	function setEmsEntry($reportUuid){
		$report = $this->reportDAO->getReport($reportUuid);
		$report->setEmsEntry(true);
		$this->reportDAO->updateReportOnly($report);
	}
	
	function unsetEmsEntry($reportUuid){
		$report = $this->reportDAO->getReport($reportUuid);
		$report->setEmsEntry(false);
		$this->reportDAO->updateReportOnly($report);
	}
	
	function setApproval($reportUuid){
		$report = $this->reportDAO->getReport($reportUuid);
		$report->setManagerApproved(true);
		$this->reportDAO->updateReportOnly($report);
	}
	
	function unsetApproval($reportUuid){
		$report = $this->reportDAO->getReport($reportUuid);
		$report->setManagerApproved(false);
		$this->reportDAO->updateReportOnly($report);
	}
	
}