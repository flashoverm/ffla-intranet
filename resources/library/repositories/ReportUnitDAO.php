<?php

require_once "BaseDAO.php";

class ReportUnitDAO extends BaseDAO{
	
    protected $userDao;
	protected $staffPositionDAO;
	protected $engineDAO;
	
	function __construct(PDO $pdo, UserDAO $userDao, StaffPositionDAO $staffPositionDAO, EngineDAO $engineDAO) {
		parent::__construct($pdo, "report_unit");
		$this->userDao = $userDao;
		$this->staffPositionDAO = $staffPositionDAO;
		$this->engineDAO = $engineDAO;
	}
	
	function save(ReportUnit $reportUnit){
		return $this->insertReportUnit($reportUnit);
	}
	
	function getReportUnits($reportUuid){
		$statement = $this->db->prepare("SELECT * FROM report_unit WHERE report = ?");
		
		if ($statement->execute(array($reportUuid))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	protected function getUnitStaff(string $unitUuid){
		$statement = $this->db->prepare("SELECT * FROM report_staff WHERE unit = ?");
		
		if ($statement->execute(array($unitUuid))) {
			return $this->handleResult($statement, true, "resultToReportStaffObject");
		}
		return false;
	}
	
	function deleteReportUnits(string $reportUuid){
		$statement = $this->db->prepare("DELETE FROM report_staff WHERE unit IN (SELECT uuid FROM report_unit WHERE report = ?)");
		
		if ($statement->execute(array($reportUuid))) {
			
			$statement = $this->db->prepare("DELETE FROM report_unit WHERE report = ?");
			
			if ($statement->execute(array($reportUuid))) {
				return true;
			}
		}
		return false;
	}

	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new ReportUnit($result['unit'], $result['date'], $result['start_time'], $result['end_time']);
		$object->setUuid($result['uuid']);
		if($result['km'] != NULL){
			$object->setKm($result['km']);
		}
		$object->setReportUuid($result['report']);
		$object->setStaff($this->getUnitStaff($result['uuid']));
		return $object;
	}
	
	protected function resultToReportStaffObject($result){
	    $user = null;
	    if($result['user'] != null){
	        $user = $this->userDao->getUserByUUID($result['user']);
	    }
	    $engine = null;
	    if($result['engine'] != null){
	        $engine = $this->engineDAO->getEngine($result['engine']);
	    }
	    
		$object = new ReportStaff(
				$this->staffPositionDAO->getStaffPosition($result['position']),
		        $user
			);
		$object->setName($result['name']);
		$object->setEngine($engine);
		$object->setUuid($result['uuid']);
		$object->setUnitUuid($result['unit']);
		return $object;
	}
	
	protected function insertReportUnit(ReportUnit $reportUnit){
		
		$uuid = $this->generateUuid();
		$reportUnit->setUuid($uuid);
		
		$statement = $this->db->prepare("INSERT INTO report_unit (uuid, date, start_time, 
		end_time, unit, km, report)
		VALUES (?, ?, ?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array(
				$uuid, $reportUnit->getDate(), $reportUnit->getStartTime(), 
				$reportUnit->getEndTime(), $reportUnit->getUnitName(), 
				$reportUnit->getKm(), $reportUnit->getReportUuid()
		));
		
		if ($result) {
			
			foreach($reportUnit->getStaff() as $staff){
				$staff->setUnitUuid($uuid);
				$this->insertReportStaff($staff);
			}
			
			return $reportUnit;
		}
		return false;
	}
	
	protected function insertReportStaff(ReportStaff $staff){
		$uuid = $this->generateUuid();
		$staff->setUuid($uuid);
		
		$statement = $this->db->prepare("INSERT INTO report_staff (uuid, position, name, engine, user, unit)
		VALUES (?, ?, ?, ?, ?, ?)");
		
		$engine = null;
		if($staff->getEngine() != null){
		    $engine = $staff->getEngine()->getUuid();
		}
		$user = null;
		if($staff->getUser() != null){
		    $user = $staff->getUser()->getUuid();
		}
		
		$result = $statement->execute(array(
			$uuid, $staff->getPosition()->getUuid(), 
		    $staff->getName(), $engine,
		    $user, $staff->getUnitUuid()
		));
		
		if ($result) {
		    return $staff;
		}
		return false;
	}

	protected function createTable(){
		$statement = $this->db->prepare("CREATE TABLE report_unit (
                          uuid CHARACTER(36) NOT NULL,
						  date DATE NOT NULL,
                          start_time TIME NOT NULL,
                          end_time TIME NOT NULL,
						  unit VARCHAR(96),
						  km SMALLINT,
						  report CHARACTER(36) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (report) REFERENCES report(uuid)
                          )");
		$result = $statement->execute();

		if ($result) {
			$statement = $this->db->prepare("CREATE TABLE report_staff (
                          uuid CHARACTER(36) NOT NULL,
						  position CHARACTER(36) NOT NULL,
                          name VARCHAR(96),
                          engine CHARACTER(36),
                          user CHARACTER(36),
						  unit CHARACTER(36) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (unit) REFERENCES report_unit(uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid),
                          FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
			$result = $statement->execute();
			
			if ($result) {
				return true;
			}
		}
		return false;
	}
	
}