<?php

require_once "BaseDAO.php";

class ReportDAO extends BaseDAO{
	
	const ORDER_DATE = "date";
	const ORDER_START = "start_time";
	const ORDER_END = "end_time";
	const ORDER_TYPE = "type";
	const ORDER_TITLE = "title";
	const ORDER_ENGINE = "name";
	const ORDER_INCIDENTS = "noIncidents";
	const ORDER_APPROVED = "managerApproved";
	const ORDER_EMSENTRY = "emsEntry";
	
	protected $engineDAO;
	protected $eventTypeDAO;
	protected $reportUnitDAO;
	protected $userDAO;
	
	function __construct(PDO $pdo, EngineDAO $engineDAO, EventTypeDAO $eventTypeDAO, ReportUnitDAO $reportUnitDAO, UserDAO $userDAO) {
		parent::__construct($pdo, "report");
		$this->engineDAO = $engineDAO;
		$this->eventTypeDAO = $eventTypeDAO;
		$this->reportUnitDAO = $reportUnitDAO;
		$this->userDAO = $userDAO;
	}
	
	function save(Report $report){
		$saved = null;
		if($this->uuidExists($report->getUuid(), $this->tableName)){
			$saved = $this->updateReport($report);
		} else {
			$saved = $this->insertReport($report);
		}
		return $saved;
	}
	
	function getReport($reportUuid) {
		$statement = $this->db->prepare("SELECT * FROM report WHERE uuid = ?");
		
		if ($statement->execute(array($reportUuid))) {
			return $this->handleResult($statement, false);
		}
		return false;	
	}
	
	function getReports(array $getParams, $dateOrder = "DESC"){
		$query = "SELECT report.*, engine.name FROM report, engine
			WHERE engine.uuid = report.engine
			ORDER BY date " . $dateOrder;
		
		return $this->executeQuery($query, null, $getParams);
	}
	
	function getUnapprovedReports(array $getParams, $dateOrder = "DESC"){
	    $query = "SELECT report.*, engine.name FROM report, engine
			WHERE engine.uuid = report.engine
            AND report.managerApproved = 0
			ORDER BY date " . $dateOrder;
	    
	    return $this->executeQuery($query, null, $getParams);
	}
	
	function getReportsByEngine($engineUuid, array $getParams, $dateOrder = "DESC"){
		$query = "SELECT report.*, engine.name FROM report, engine
			WHERE engine = ? AND engine.uuid = report.engine 
			ORDER BY date " . $dateOrder;
		
		return $this->executeQuery($query, array($engineUuid), $getParams);
	}
	
	function getReportsByCreator($creatorUuid, array $getParams, $dateOrder = "DESC"){
		$query = "SELECT report.*, engine.name FROM report, engine
			WHERE creator = ? AND engine.uuid = report.engine
			ORDER BY date " . $dateOrder;
		
		return $this->executeQuery($query, array($creatorUuid), $getParams);
	}
	
	function getReportsByCreatorOrStaff($userUuid, array $getParams, $dateOrder = "DESC"){
	    $query = "SELECT report.*, engine.name 
            FROM report, engine
			WHERE (creator = ? OR 
                report.uuid IN (SELECT report FROM report_unit, report_staff WHERE report_unit.uuid = report_staff.unit AND report_staff.user = ?)
            ) 
            AND engine.uuid = report.engine
			ORDER BY date " . $dateOrder;
	    
	    return $this->executeQuery($query, array($userUuid, $userUuid), $getParams);
	}
	
	function filterReports($reports, $typeUuid, $from, $until) {
		$data = array ();
		foreach($reports as $report){
			
			if($report->getDate() >= $from && $report->getDate() <= $until ){
				
				if($typeUuid == -1 || $report->getType()->getUuid() == $typeUuid
						//Special case - TODO find a better way
						|| ( $typeUuid == -2 && strpos($report->getType()->getType(), "Theaterwache") > -1 ) ){
					$data [] = $report;
				}
			}
		}
		return $data;
	}
	
	function deleteReport($reportUuid){
		if($this->reportUnitDAO->deleteReportUnits($reportUuid)){
			
			$statement = $this->db->prepare("DELETE FROM report WHERE uuid= ?");
			
			if ($statement->execute(array($reportUuid))) {
				return true;
			}
		}
		return false;
	}
	
	function getReportsByYear($year){
	    $statement = $this->db->prepare("SELECT * FROM report WHERE YEAR(date) = ?");
	    if ($statement->execute(array($year))) {
	        return $this->handleResult($statement, false);
	    }
	    return false;	
	}
	
	function getReportsByYearByEngine($year, $engineUuid){
	    $statement = $this->db->prepare("SELECT * FROM report 
            WHERE YEAR(date) = ? AND engine = ?");
	    
	    if ($statement->execute(array($year, $engineUuid))) {
	        return $this->handleResult($statement, false);
	    }
	    return false;
	}
	
	function getReportYears(){
	    $statement = $this->db->prepare("SELECT DISTINCT(YEAR(date)) as year FROM report ORDER BY year");
	    
	    if ($statement->execute()) {
	        return $statement->fetchAll(PDO::FETCH_COLUMN);
	    }
	    return false;
	}
	
	function getReportsByStaff($userUuid){
	    $statement = $this->db->prepare("SELECT report.* 
            FROM report, report_unit, report_staff 
            WHERE report.uuid = report_unit.report 
            AND report_unit.uuid = report_staff.unit 
            AND report_staff.user = ? 
            ORDER BY report.date DESC; 
        ");
	    
	    if ($statement->execute(array($userUuid))) {
	        return $this->handleResult($statement, true);
	    }
	    return false;	
	}
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new Report();
		$object->setUuid($result['uuid']);
		$object->setDate($result['date']);
		$object->setStartTime($result['start_time']);
		$object->setEndTime($result['end_time']);
		$object->setType($this->eventTypeDAO->getEventType($result['type']));
		$object->setTypeOther($result['type_other']);
		$object->setTitle($result['title']);
		$object->setEngine($this->engineDAO->getEngine($result['engine']));
		$object->setCreator($this->userDAO->getUserByUUID($result['creator']));
		$object->setNoIncidents($result['noIncidents']);
		$object->setIlsEntry($result['ilsEntry']);
		$object->setEmsEntry($result['emsEntry']);
		$object->setManagerApproved($result['managerApproved']);
		if($result['managerApprovedBy'] != null){
			$object->setManagerApprovedBy($this->userDAO->getUserByUUID($result['managerApprovedBy']));
		}
		$object->setManagerApprovedDate($result['managerApprovedDate']);
		$object->setReportText($result['report']);
		$object->setUnits($this->reportUnitDAO->getReportUnits($result['uuid']));
		$object->setCreateDate($result['createDate']);
		return $object;
	}
	
	protected function insertReport(Report $report){
		$uuid = $this->generateUuid();
		$report->setUuid($uuid);
		
		$creator = null;
		if($report->getCreator() != null){
		    $creator = $report->getCreator()->getUuid();
		}
		
		$approvedBy = null;
		if($report->getManagerApprovedBy() != null){
			$approvedBy = $report->getManagerApprovedBy()->getUuid();
		}
		
		$statement = $this->db->prepare("INSERT INTO report (uuid, event, date, start_time, 
			end_time, type, type_other, title, engine, creator, noIncidents, ilsEntry, 
			report, emsEntry, createDate, managerApproved, managerApprovedBy, managerApprovedDate)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
		
		$result = $statement->execute(array(
				$uuid, $report->getEventUuid(), $report->getDate(),
				$report->getStartTime(), $report->getEndTime(),
				$report->getType()->getUuid(), $report->getTypeOther(),
				$report->getTitle(), $report->getEngine()->getUuid(),
		        $creator, $report->getNoIncidents(),
				$report->getIlsEntry(), $report->getReportText(),
				$report->getEmsEntry(), $report->getManagerApproved(),
				$approvedBy, $report->getManagerApprovedDate()
		));
				
		if ($result) {
			
			foreach($report->getUnits() as $reportUnit){
				$reportUnit->setReportUuid($uuid);
				$this->reportUnitDAO->save($reportUnit);
			}
			
			return $this->getReport($uuid);
		}
		return false;
	}
		
	protected function updateReport(Report $report){
		if ($this->updateReportOnly($report)) {
			
			if($this->reportUnitDAO->deleteReportUnits($report->getUuid())){
				
				foreach($report->getUnits() as $reportUnit){
					if($reportUnit->getReportUuid() == NULL){
						$reportUnit->setReportUuid($report->getUuid());
					}
					if(! $this->reportUnitDAO->save($reportUnit) ) {
						return false;
					}
				}
				
				return $this->getReport($report->getUuid());
			}
		}
		return false;
	}
	
	function updateReportOnly(Report $report){
	    $creator = null;
	    if($report->getCreator() != null){
	        $creator = $report->getCreator()->getUuid();
	    }
	    
	    $approvedBy = null;
	    if($report->getManagerApprovedBy() != null){
	    	$approvedBy = $report->getManagerApprovedBy()->getUuid();
	    }
	    
		$statement = $this->db->prepare("UPDATE report
		SET event = ?, date = ?, start_time = ?, end_time = ?, type = ?, type_other = ?,
		title = ?, engine = ?, creator = ?, createDate = ?, noIncidents = ?, ilsEntry = ?, report = ?,
		emsEntry = ?, managerApproved = ?, managerApprovedBy = ?, managerApprovedDate = ?
		WHERE uuid = ?");
		
		$result = $statement->execute(array(
				$report->getEventUuid(), $report->getDate(),
				$report->getStartTime(), $report->getEndTime(),
				$report->getType()->getUuid(), $report->getTypeOther(),
				$report->getTitle(), $report->getEngine()->getUuid(),
		        $creator, $report->getCreateDate(), $report->getNoIncidents(),
				$report->getIlsEntry(), $report->getReportText(),
				$report->getEmsEntry(), $report->getManagerApproved(),
				$approvedBy, $report->getManagerApprovedDate(),
				$report->getUuid()
		));
		
		if ($result) {
			return true;
		}
		return false;
	}

	protected function createTable(){
		$statement = $this->db->prepare("CREATE TABLE report (
                          uuid CHARACTER(36) NOT NULL,
                          event CHARACTER(36),
						  date DATE NOT NULL,
                          start_time TIME NOT NULL,
                          end_time TIME NOT NULL,
                          type CHARACTER(36) NOT NULL,
                          type_other VARCHAR(96),
						  title VARCHAR(96),
                          engine CHARACTER(36) NOT NULL,
						  creator VARCHAR(128) NOT NULL,
						  createDate datetime NOT NULL,
                          noIncidents BOOLEAN NOT NULL default 0,
                          ilsEntry BOOLEAN NOT NULL default 0,
						  emsEntry BOOLEAN NOT NULL default 0,
						  managerApproved BOOLEAN NOT NULL default 0,
						  managerApprovedDate datetime,
						  managerApprovedBy CHARACTER(36),
                          report TEXT,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (type) REFERENCES eventtype(uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid),
						  FOREIGN KEY (managerApprovedBy) REFERENCES user(uuid),
                          )");
		
		$result = $statement->execute();

		if ($result) {
			return true;
		}
		return false;
	}
	
}