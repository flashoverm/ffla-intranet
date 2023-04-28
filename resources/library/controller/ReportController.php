<?php 

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class ReportController extends BaseController{
    
    
    public static function get_report_create_link($event_uuid){
        global $config;
        return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/" . $event_uuid;
    }
    
    public static function get_report_link($report_uuid){
        global $config;
        return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/view/" . $report_uuid;
    }
    
	
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
	
	function setApproval($reportUuid, $currentUser){
		$report = $this->reportDAO->getReport($reportUuid);
		$report->setManagerApprovedDate(date('Y-m-d H:i:s'));
		$report->setManagerApprovedBy($currentUser);
		$report->setManagerApproved(true);
		return $this->reportDAO->updateReportOnly($report);
	}
	
	function unsetApproval($reportUuid){
		$report = $this->reportDAO->getReport($reportUuid);
		$report->setManagerApproved(false);
		$report->setManagerApprovedDate(null);
		$report->setManagerApprovedBy(null);
		return $this->reportDAO->updateReportOnly($report);
	}
	
}