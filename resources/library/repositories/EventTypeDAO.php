<?php

require_once "BaseDAO.php";

class EventTypeDAO extends BaseDAO{
	
	function __construct(PDO $pdo) {
		parent::__construct($pdo);
	}
	
	function save(EventType $eventType){
		$statement = $this->db->prepare("INSERT INTO eventtype (uuid, type, isseries) VALUES (?, ?, ?)");
		
		$result = $statement->execute(array($eventType->getUuid(), $eventType->getType(), $eventType->getIsSeries()));
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	function getEventType($uuid) {
		$statement = $this->db->prepare("SELECT * FROM eventtype WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
		
	function getEventTypes() {
		$statement = $this->db->prepare("SELECT * FROM eventtype ORDER BY type");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;		
	}
	
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new EventType($result['uuid'], $result['type'], $result['isseries']);
		return $object;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE eventtype (
                          uuid CHARACTER(36) NOT NULL,
						  type VARCHAR(64) NOT NULL,
                          isseries BOOLEAN NOT NULL,
                          PRIMARY KEY  (uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			$this->initializeEngines();
			return true;
		} else {
			return false;
		}
	}
	
	protected function initializeEventTypes(){
		$this->save(new EventType("325FF3CA-62BE-3F3E-88D8-A1C932BE600B", "Theaterwache", true ));
		$this->save(new EventType("C5503C1D-E08C-4850-27CB-563302EC9318", "Theaterwache Schüler", true  ));
		$this->save(new EventType("00155A58-8720-29CF-42F0-713895C7BFDA", "Theaterwache Prantlgarten", true  ));
		$this->save(new EventType("84D42DC4-0BCD-3DD4-C4D4-7D68CAB559D0", "Residenzwache", false ));
		$this->save(new EventType("5B3243FF-2D65-A0E6-E92D-0B2B8DC38D32", "Rathauswache", false ));
		$this->save(new EventType("7C5B9E95-0EC0-DFD5-ED7E-9A736BAD0AD1", "Wache Sparkassenarena", false ));
		$this->save(new EventType("E438FF03-C5FA-EB29-59F4-CD76B11054C3", "Burgwache", false ));
		$this->save(new EventType("D5156566-8F0D-FC74-983E-92B82A5F2917", "Dultwache", true  ));
		$this->save(new EventType("38579dc9-3c32-11e9-a62d-0800272f1758", "Wache Niederbayern-Schau", true ));
		$this->save(new EventType("25d9b1d7-3c32-11e9-a62d-0800272f1758", "Wache Landshuter Hochzeit", true  ));
		$this->save(new EventType("3D79FB4B-C85E-DF81-863B-60C0DB7601C9", "Sonstige Wache", false ));
	}
}