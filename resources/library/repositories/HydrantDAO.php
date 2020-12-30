<?php

require_once "BaseDAO.php";

class HydrantDAO extends BaseDAO{
	
	protected $engineDAO;
	
	function __construct(PDO $pdo, EngineDAO $engineDAO) {
		parent::__construct($pdo);
		$this->engineDAO = $engineDAO;
	}
	
	function save(Hydrant $hydrant){
		$saved = null;
		if($this->uuidExists($hydrant->getUuid(), "hydrant")){
			$saved = $this->updateHydrant($hydrant);
		} else {
			$saved = $this->insertHydrant($hydrant);
		}
		return $saved;
	}
	
	function getHydrants(){
		$statement = $this->db->prepare("SELECT * FROM hydrant");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getHydrantsOfEngine($engineUuid){
		$statement = $this->db->prepare("SELECT * FROM hydrant WHERE engine = ? AND operating = TRUE");
		
		if ($statement->execute(array($engineUuid))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getHydrantsOfStreet(string $street){
		$statement = $this->db->prepare("SELECT * FROM hydrant WHERE street = ? AND operating = TRUE");
		
		if ($statement->execute(array($street))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getHydrantByUuid(string $uuid){
		$statement = $this->db->prepare("SELECT * FROM hydrant WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getHydrantByFid(int $fid){
		$statement = $this->db->prepare("SELECT * FROM hydrant WHERE fid = ?");
		
		if ($statement->execute(array($fid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getHydrantByHy(int $hy){
		$statement = $this->db->prepare("SELECT * FROM hydrant WHERE hy = ?");
		
		if ($statement->execute(array($hy))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getUncheckedHydrantsOfEngine($engineUuid){
		$statement = $this->db->prepare("SELECT *
        FROM hydrant
        LEFT JOIN (
        	SELECT hydrant_inspection.hydrant, MAX(inspection.date) AS lastcheck
            FROM inspection, hydrant_inspection
            WHERE hydrant_inspection.inspection = inspection.uuid
        	GROUP BY hydrant_inspection.hydrant
            ) AS lastchecks ON hydrant.uuid = lastchecks.hydrant
        WHERE (lastcheck IS NULL OR (lastcheck + INTERVAL hydrant.cycle YEAR) < NOW())
        	AND engine = ? AND checkbyff = TRUE AND operating = true
        ORDER BY lastcheck");
		
		if ($statement->execute(array($engineUuid))) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getStreetList(){
		$statement = $this->db->prepare("SELECT DISTINCT street FROM hydrant ORDER BY street");
		
		if ($statement->execute()) {
			return $statement->fetchAll(PDO::FETCH_COLUMN);
		}
		return false;
	}
	
	function getDistrictList(){
		$statement = $this->db->prepare("SELECT DISTINCT district FROM hydrant ORDER BY district");
		
		if ($statement->execute()) {
			return $statement->fetchAll(PDO::FETCH_COLUMN);
		}
		return false;
	}
	
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new Hydrant();
		$object->setUuid($result['uuid']);
		$object->setFid($result['fid']);
		$object->setHy($result['hy']);
		$object->setStreet($result['street']);
		$object->setType($result['type']);
		$object->setCheckByFF($result['checkbyff']);
		$object->setOperating($result['operating']);
		$object->setDistrict($result['district']);
		$object->setLat($result['lat']);
		$object->setLng($result['lng']);
		$object->setEngine($this->engineDAO->getEngine($result['engine']));
		$object->setCycle($result['cycle']);
		
		if($result['map']){
			$object->setMap($result['map']);
		} else {
			$object->setMap(NULL);
		}
		
		if(isset($result['lastcheck'])){
			$object->setLastCheck($result['lastcheck']);
		} else {
			$object->setLastCheck(NULL);
		}
		
		return $object;
	}
	
	protected function insertHydrant(Hydrant $hydrant){
		$uuid = $this->getUuid();
		
		$statement = $this->db->prepare("INSERT INTO hydrant
            (uuid, fid, hy, street, type, checkbyff, district, lat, lng, engine, cycle, operating)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($uuid, $hydrant->getFid(), $hydrant->getHy(), $hydrant->getStreet(), 
				$hydrant->getType(), $hydrant->getCheckByFF(), $hydrant->getDistrict(), $hydrant->getLat(), 
				$hydrant->getLng(), $hydrant->getEngine()->getUuid(), $hydrant->getCycle(), $hydrant->getOperating()
		));
		
		if ($result) {
			return $this->getUserByUUID($uuid);
		}
		return false;
	}
	
	protected function updateHydrant(Hydrant $hydrant){
		$statement = $this->db->prepare("UPDATE hydrant
		SET fid = ?, hy = ?, street = ?, type = ?, checkbyff = ?, district = ?, lat = ?, lng = ?, engine = ?, cycle = ?, operating = ?
		WHERE uuid = ?");
		
		$result = $statement->execute(array($hydrant->getFid(), $hydrant->getHy(), $hydrant->getStreet(), $hydrant->getType(), 
				$hydrant->getCheckByFF(), $hydrant->getDistrict(), $hydrant->getLat(), $hydrant->getLng(), 
				$hydrant->getEngine()->getUuid(), $hydrant->getCycle(), $hydrant->getOperating(), $hydrant->getUuid()
		));
		
		if ($result) {
			return $hydrant;
		}
		return false;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE hydrant (
						  uuid CHARACTER(36) NOT NULL,
                          fid INTEGER NOT NULL,
                          hy INTEGER NOT NULL,
                          street VARCHAR(255) NOT NULL,
                          type VARCHAR(255) NOT NULL,
                          checkbyff BOOL NOT NULL,
                          operating BOOL NOT NULL,
                          district VARCHAR(255) NOT NULL,
                          lat DECIMAL(10, 8) NOT NULL,
                          lng DECIMAL(10, 8) NOT NULL,
                          engine CHARACTER(36) NOT NULL,
						  map VARCHAR(255),
                          cycle TINYINT NOT NULL,
                          PRIMARY KEY  (uuid),
                          FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
}