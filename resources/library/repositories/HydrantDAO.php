<?php

require_once "BaseDAO.php";

class HydrantDAO extends BaseDAO{
    
    const ORDER_HY = "hy";
    const ORDER_FID = "fid";
    const ORDER_STREET = "street";
    const ORDER_DISTRICT = "district";
    const ORDER_ENGINE = "name";
    const ORDER_OPERATING = "operating";
    const ORDER_TYPE = "type";
    const ORDER_LASTCHECK = "lastcheck";
	
	protected $engineDAO;
	
	function __construct(PDO $pdo, EngineDAO $engineDAO) {
		parent::__construct($pdo, "hydrant");
		$this->engineDAO = $engineDAO;
	}
	
	function save(Hydrant $hydrant){
		$saved = null;
		if($this->uuidExists($hydrant->getUuid(), $this->tableName)){
			$saved = $this->updateHydrant($hydrant);
		} else {
			$saved = $this->insertHydrant($hydrant);
		}
		return $saved;
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
	
	
	function getHydrants($getParams){
	    $query = "SELECT hydrant.*, lastchecks.lastcheck, engine.name
			FROM hydrant
			LEFT JOIN (
        		SELECT hydrant_inspection.hydrant, MAX(inspection.date) AS lastcheck
            	FROM inspection, hydrant_inspection
            	WHERE hydrant_inspection.inspection = inspection.uuid
        		GROUP BY hydrant_inspection.hydrant
            ) AS lastchecks ON hydrant.uuid = lastchecks.hydrant
            INNER JOIN engine ON hydrant.engine = engine.uuid";
		
	    return $this->executeQuery($query, null, $getParams);
	}
	
	function getHydrantsOfEngine($engineUuid, $getParams){
	    $query = "SELECT hydrant.*, lastchecks.lastcheck, engine.name
			FROM hydrant 
			LEFT JOIN (
        		SELECT hydrant_inspection.hydrant, MAX(inspection.date) AS lastcheck
            	FROM inspection, hydrant_inspection
            	WHERE hydrant_inspection.inspection = inspection.uuid
        		GROUP BY hydrant_inspection.hydrant
            ) AS lastchecks ON hydrant.uuid = lastchecks.hydrant
            INNER JOIN engine ON hydrant.engine = engine.uuid
			WHERE engine = ? AND operating = TRUE";
		
	    return $this->executeQuery($query, array($engineUuid), $getParams);
	}
	
	function getHydrantsOfStreet(string $street, $getParams){
		$query = "SELECT hydrant.*, engine.name FROM hydrant, engine WHERE street = ? AND operating = TRUE AND engine.uuid = hydrant.engine";
		
		return $this->executeQuery($query, array($street), $getParams);
	}

	function getUncheckedHydrantsOfEngine($engineUuid, $getParams){
	    $query = "SELECT hydrant.*, lastchecks.lastcheck, engine.name
        FROM hydrant
        LEFT JOIN (
        	SELECT hydrant_inspection.hydrant, MAX(inspection.date) AS lastcheck
            FROM inspection, hydrant_inspection
            WHERE hydrant_inspection.inspection = inspection.uuid
        	GROUP BY hydrant_inspection.hydrant
            ) AS lastchecks ON hydrant.uuid = lastchecks.hydrant
        INNER JOIN engine ON hydrant.engine = engine.uuid
        WHERE (lastcheck IS NULL OR (lastcheck + INTERVAL hydrant.cycle YEAR) < NOW())
        	AND engine = ? AND checkbyff = TRUE AND operating = true
        ORDER BY lastcheck";
		
	    return $this->executeQuery($query, array($engineUuid), $getParams);
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
		$uuid = $this->generateUuid();
		$hydrant->setUuid($uuid);
		
		$statement = $this->db->prepare("INSERT INTO hydrant
            (uuid, fid, hy, street, type, checkbyff, district, lat, lng, engine, cycle, operating)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		
		$result = $statement->execute(array($uuid, $hydrant->getFid(), $hydrant->getHy(), $hydrant->getStreet(), 
				$hydrant->getType(), $hydrant->getCheckByFF(), $hydrant->getDistrict(), $hydrant->getLat(), 
				$hydrant->getLng(), $hydrant->getEngine()->getUuid(), $hydrant->getCycle(), $hydrant->getOperating()
		));
		
		if ($result) {
			return $this->getHydrantByUuid($uuid);
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
                          checkbyff BOOL NOT NULL default 0,
                          operating BOOL NOT NULL default 0,
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
		}
		return false;
	}
}