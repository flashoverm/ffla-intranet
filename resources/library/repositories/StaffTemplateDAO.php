<?php 

require_once "BaseDAO.php";

class StaffTemplateDAO extends BaseDAO {
	
	protected $staffPositionDAO;
	protected $eventTypeDAO;
	
	function __construct(StaffPositionDAO $staffPositionDAO, EventTypeDAO $eventTypeDAO) {
		parent::__construct();
		$this->staffPositionDAO = $staffPositionDAO;
		$this->eventTypeDAO = $eventTypeDAO;
	}
	
	function save(StaffTemplate $staffTemplate){
		
		$eventUuid = $staffTemplate->getEventType()->getUuid();
		
		$this->deleteStaffTemplate($eventUuid);
		
		foreach($staffTemplate->getStaffPositions() as $position){
			$this->insertTemplatePosition($staffTemplate->getEventType()->getUuid(), $position->getUuid());
		}
		return false;
	}
	
	function getStaffTemplate($eventTypeUuid){
		$statement = $this->db->prepare("SELECT stafftemplate.*
				FROM stafftemplate, staffposition
				WHERE staffposition.uuid = stafftemplate.staffposition 
			    AND stafftemplate.eventtype = ?
				ORDER BY staffposition.list_index");
		
		if ($statement->execute(array($eventTypeUuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function deleteStaffTemplate($eventTypeUuid){
		$statement = $this->db->prepare("DELETE FROM stafftemplate WHERE eventtype= ?");
		
		if ($statement->execute(array($eventTypeUuid))) {
			return true;
		}
		return false;
	}
	
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		
	}
	
	protected function handleResult($statement, $returnAlwaysArray = false, $callback = NULL){
		$object = NULL;
		
		while($row = $statement->fetch()) {
			if($object == NULL){
				$object = new StaffTemplate();
				$object->setEventType($this->eventTypeDAO->getEventType($row['eventtype']));
			}
			$object->addStaffposition($this->staffPositionDAO->getStaffPosition($row['staffposition']));
		}
		return $object;
	}
	
	protected function insertTemplatePosition($eventtypeUuid, $staffpositionUuid){
		$uuid = $this->getUuid();
		
		$statement = $this->db->prepare("INSERT INTO stafftemplate (uuid, eventtype, staffposition)
		VALUES (?, ?, ?)");
		
		if ($statement->execute(array($uuid, $eventtypeUuid, $staffpositionUuid))) {
			return true;
		}
		return false;
	}
	
	protected function templateExists($eventTypeUuid){
		if($eventTypeUuid == NULL){
			return false;
		}
		$statement = $this->db->prepare("SELECT * FROM stafftemplate WHERE eventtype = ?");
		$statement->execute(array($eventTypeUuid));
		
		if ($statement->execute()) {
			return $statement->rowCount() > 1;
		}
		return false;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE stafftemplate (
                          uuid CHARACTER(36) NOT NULL,
                          eventtype CHARACTER(36) NOT NULL,
                          staffposition CHARACTER(36) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (eventtype) REFERENCES eventtype(uuid),
						  FOREIGN KEY (staffposition) REFERENCES staffposition(uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function initializeStaffTemplates(){
		$staffTemplate = new StaffTemplate();
		$staffTemplate->setEventType($this->eventTypeDAO->getEventType("325FF3CA-62BE-3F3E-88D8-A1C932BE600B"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("28F8486C-1F14-4293-6BB6-59A959281FE3"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("9CB30C8D-9ABD-487E-3385-3957B0ECD560"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("9CB30C8D-9ABD-487E-3385-3957B0ECD560"));
		$this->save($staffTemplate);
		
		$staffTemplate = new StaffTemplate();
		$staffTemplate->setEventType($this->eventTypeDAO->getEventType("C5503C1D-E08C-4850-27CB-563302EC9318"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("28F8486C-1F14-4293-6BB6-59A959281FE3"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("9CB30C8D-9ABD-487E-3385-3957B0ECD560"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("9CB30C8D-9ABD-487E-3385-3957B0ECD560"));
		$this->save($staffTemplate);
		
		$staffTemplate = new StaffTemplate();
		$staffTemplate->setEventType($this->eventTypeDAO->getEventType("00155A58-8720-29CF-42F0-713895C7BFDA"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("28F8486C-1F14-4293-6BB6-59A959281FE3"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("9CB30C8D-9ABD-487E-3385-3957B0ECD560"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("9CB30C8D-9ABD-487E-3385-3957B0ECD560"));
		$this->save($staffTemplate);

		
		$staffTemplate = new StaffTemplate();
		$staffTemplate->setEventType($this->eventTypeDAO->getEventType("D5156566-8F0D-FC74-983E-92B82A5F2917"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("BE8BA2F1-11B0-F8DB-292D-8F054A797214"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("C6C83E5B-660D-33A5-3B45-B4B2E4F13F23"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("DAA45E2B-7691-3CF3-4D0D-0C1A39DD0003"));
		$staffTemplate->addStaffposition($this->staffPositionDAO->getStaffPosition("DAA45E2B-7691-3CF3-4D0D-0C1A39DD0003"));
		$this->save($staffTemplate);		
	}
	
	
}
