<?php

require_once "BaseDAO.php";

class InspectionDAO extends BaseDAO{
	
	const ORDER_DATE = "date";
	const ORDER_ENGINE = "enginename";
	const ORDER_NAMES = "name";
	const ORDER_VEHICLE = "vehicle";
	const ORDER_HYDRANTS = "hydrants";
	
	protected $hydrantDAO;
	protected $engineDAO;
	
	function __construct(PDO $pdo, HydrantDAO $hydrantDAO, EngineDAO $engineDAO) {
		parent::__construct($pdo, "inspection");
		$this->hydrantDAO = $hydrantDAO;
		$this->engineDAO = $engineDAO;
	}
	
	function save(Inspection $inspection){
		$saved = null;
		if($this->uuidExists($inspection->getUuid(), $this->tableName)){
			$saved = $this->updateInspection($inspection);
		} else {
			$saved = $this->insertInspection($inspection);
		}
		if($saved != null){
			return $saved;
		}
		return false;
	}
	
	function getInspection($uuid) {
		$statement = $this->db->prepare("SELECT * FROM inspection WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getInspections($getParams){
		$query = "SELECT inspection.*, engine.name as enginename, count(*) as hydrants
			FROM inspection, engine, hydrant_inspection
			WHERE inspection.engine = engine.uuid AND inspection.uuid = hydrant_inspection.inspection 
			GROUP BY hydrant_inspection.inspection 
			ORDER BY date DESC";
		
		return $this->executeQuery($query, null, $getParams);
	}
	
	function getInspectionsByEngine($engineUuid, $getParams){
		$query = "SELECT inspection.*, engine.name as enginename, count(*) as hydrants 
			FROM inspection, engine, hydrant_inspection 
			WHERE engine = ? AND inspection.engine = engine.uuid 
				AND inspection.uuid = hydrant_inspection.inspection 
			GROUP BY hydrant_inspection.inspection 
			ORDER BY date DESC";
		
		return $this->executeQuery($query, array($engineUuid), $getParams);
	}
	
	function getInspectedHydrant($inspectionUuid, $hydrantUuid){
		$statement = $this->db->prepare("SELECT * FROM hydrant_inspection WHERE inspection = ? AND hydrant = ?");
		
		if ($statement->execute(array($inspectionUuid, $hydrantUuid))) {
			return $this->handleResult($statement, false, "resultToInspectedHydrantObject");
		}
		return false;
	}
	
	function deleteInspection($uuid){
		$statement = $this->db->prepare("DELETE FROM inspection WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			$statement = $this->db->prepare("DELETE FROM hydrant_inspection WHERE inspection = ?");
			if ($statement->execute(array($uuid))) {
				return true;
			}
		}
		return false;
	}
	
	function getInspectionYears(){
		$statement = $this->db->prepare("SELECT DISTINCT(EXTRACT(YEAR FROM date)) as year FROM inspection ORDER BY date DESC");
		
		if ($statement->execute()) {
			return $statement->fetchAll(PDO::FETCH_COLUMN);
		}
		return false;
	}
	
	function getHydrantsByYear($year){
		$statement = $this->db->prepare("SELECT engine.name as engine, COUNT(*) as count 
			FROM inspection, hydrant_inspection, engine
			WHERE inspection.uuid = hydrant_inspection.inspection
			AND inspection.engine = engine.uuid
			AND EXTRACT(YEAR FROM inspection.date) = ?
			GROUP BY inspection.engine
			ORDER BY engine.name");
		
		if ($statement->execute(array($year))) {
			return $statement->fetchAll();
		}
		return false;
	}
	
	protected function getInspectedHydrants($inspectionUuid){
		$statement = $this->db->prepare("SELECT * FROM hydrant_inspection WHERE inspection = ? ORDER BY idx");
		
		if ($statement->execute(array($inspectionUuid))) {
			return $this->handleResult($statement, true, "resultToInspectedHydrantObject");
		}
		return false;
	}
	
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new Inspection();
		$object->setUuid($result['uuid']);
		$object->setDate($result['date']);
		$object->setVehicle($result['vehicle']);
		$object->setName($result['name']);
		$object->setNotes($result['notes']);
		$object->setEngine($this->engineDAO->getEngine($result['engine']));
		$object->setInspectedHydrants($this->getInspectedHydrants($result['uuid']));
		return $object;
	}
	
	protected function resultToInspectedHydrantObject($result){
		$object = new InspectedHydrant();
		$object->setIndex($result['idx']);
		$object->setType($result['type']);
		
		$criteria = json_decode($result['criteria']);
		foreach ( $criteria as $criterion ){
			if(is_array($criterion)){
				$object->addCriterion($criterion['hy_idx'], $criterion['idx'], $criterion['value']);
			} else if(is_object($criterion)) {
				//Fallback for older version: If criteria are persisted as object -> parse object
				$object->addCriterion($criterion->hy_idx, $criterion->idx, $criterion->value);
			}
		}		
		$object->setHydrant($this->hydrantDAO->getHydrantByUuid($result['hydrant']));
		return $object;
	}

	protected function insertInspection(Inspection $inspection){
		$uuid = $this->generateUuid();
		$inspection->setUuid($uuid);
		
		$statement = $this->db->prepare("INSERT INTO inspection (uuid, date, vehicle, name, notes, engine) VALUES (?, ?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($uuid, $inspection->getDate(), $inspection->getVehicle(),
				$inspection->getName(), $inspection->getNotes(), $inspection->getEngine()->getUuid()));
		
		if ($result) {
			foreach($inspection->getInspectedHydrants() as $inspectedHydrant){
				$this->insertInspectedHydrant($uuid, $inspectedHydrant);
			}
			return $this->getInspection($uuid);
		}
		return false;
	}
	
	protected function updateInspection(Inspection $inspection){
		$statement = $this->db->prepare("UPDATE inspection
		SET date = ?, vehicle = ?, name = ?, notes = ?, engine = ?
		WHERE uuid = ?");
		
		$result = $statement->execute(array($inspection->getDate(), $inspection->getVehicle(),
				$inspection->getName(), $inspection->getNotes(), 
				$inspection->getEngine()->getUuid(), $inspection->getUuid()
		));
		
		if ($result) {
			foreach($inspection->getInspectedHydrants() as $inspectedHydrant){
				if($this->inspectedHydrantExists($inspection->getUuid(), $inspectedHydrant)){
					$this->updateInspectedHydrant($inspection->getUuid(), $inspectedHydrant);
				} else {
					$this->insertInspectedHydrant($inspection->getUuid(), $inspectedHydrant);
				}
			}
			return $this->getInspection($inspection->getUuid());
		}
		return false;
	}
	
	protected function insertInspectedHydrant($inspectionUuid, InspectedHydrant $inspectedHydrant){
		$criteria = json_encode($inspectedHydrant->getCriteria());
		
		$statement = $this->db->prepare("INSERT INTO hydrant_inspection (inspection, hydrant, idx, type, criteria) VALUES (?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($inspectionUuid, $inspectedHydrant->getHydrant()->getUuid(),
				$inspectedHydrant->getIndex(), $inspectedHydrant->getType(), $criteria
		));
		
		if($result){
			return true;
		}
		return false;
	}
	
	protected function updateInspectedHydrant($inspectionUuid, InspectedHydrant $inspectedHydrant){
		$criteria = json_encode($inspectedHydrant->getCriteria());
		
		$statement = $this->db->prepare("UPDATE hydrant_inspection
		SET idx = ?, type = ?, criteria = ?
		WHERE inspection = ? AND hydrant = ?" );
	
		$result = $statement->execute(array($inspectedHydrant->getIndex(), $inspectedHydrant->getType(), 
				$criteria, $inspectionUuid, $inspectedHydrant->getHydrant()->getUuid()
		));
		
		if($result){
			return true;
		}
		return false;
	}
	
	protected function inspectedHydrantExists($inspectionUuid, InspectedHydrant $inspectedHydrant){
		if( $this->getInspectedHydrant($inspectionUuid, $inspectedHydrant->getHydrant()->getUuid()) ){
			return true;
		}
		return false;
	}
	
	protected function createTable(){
		$statement = $this->db->prepare("CREATE TABLE inspection (
                          uuid CHARACTER(36) NOT NULL,
                          date DATE NOT NULL,
                          vehicle VARCHAR(255),
                          name VARCHAR(255) NOT NULL,
                          notes VARCHAR(255),
                          engine CHARACTER(36) NOT NULL,
                          PRIMARY KEY  (uuid),
                          FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			$statement = $this->db->prepare("CREATE TABLE hydrant_inspection (
                          inspection CHARACTER(36) NOT NULL,
                          hydrant CHARACTER(36) NOT NULL,
                          idx INTEGER NOT NULL,
                          type VARCHAR(255) NOT NULL,
                          criteria VARCHAR(1022) NOT NULL,
                          PRIMARY KEY  (inspection, hydrant),
						  FOREIGN KEY (hydrant) REFERENCES hydrant(uuid),
						  FOREIGN KEY (inspection) REFERENCES inspection(uuid)
                          )");
			
			$result = $statement->execute();
			
			if ($result) {
				return true;
			}
		}
		return false;
	}
		
}