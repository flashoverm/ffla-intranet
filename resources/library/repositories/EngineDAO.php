<?php

require_once "BaseDAO.php";

class EngineDAO extends BaseDAO{
	
	function __construct(PDO $pdo) {
		parent::__construct($pdo, "engine");
	}
	
	function save(Engine $engine){
		$statement = $this->db->prepare("INSERT INTO engine (uuid, name, isadministration, shortname) VALUES (?, ?, ?, ?)");
		
		$result = $statement->execute(array($engine->getUuid(), $engine->getName(), $engine->getIsAdministration(), $engine->getShortName()));
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	function getEngine(String $uuid){
		$statement = $this->db->prepare("SELECT * FROM engine WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getEngineByName(String $name){
		$statement = $this->db->prepare("SELECT * FROM engine WHERE name = ?");
		
		if ($statement->execute(array($name))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getAdministration(){
		$statement = $this->db->prepare("SELECT * FROM engine WHERE isadministration =  TRUE");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getEngines(){	
		$statement = $this->db->prepare("SELECT * FROM engine ORDER BY name");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getEnginesWithoutAdministration(){
		$statement = $this->db->prepare("SELECT * FROM engine WHERE NOT isadministration =  FALSE ORDER BY name");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new Engine($result['uuid'], $result['name'], $result['isadministration'], $result['shortname']);
		return $object;
	}

	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE engine (
                          uuid CHARACTER(36) NOT NULL,
						  name VARCHAR(32) NOT NULL,
                          isadministration BOOLEAN NOT NULL default 0,
						  shortname VARCHAR(32) NOT NULL,
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
	
	protected function initializeEngines(){
		$this->save(new Engine("9BEECEFA-56CF-A009-0059-99DAA5FA0D4E", "Verwaltung", true, "Verw."));
		$this->save(new Engine("2BAA144B-F946-1524-E60E-7DD485FE1881", "Löschzug 1/2", false, "LZ 1/2"));
		$this->save(new Engine("9704558C-9A89-A5B0-7CDE-0321A518DCB1", "Löschzug 3", false, "LZ 3"));
		$this->save(new Engine("B0C263B5-6416-B8F5-B7A2-4ED57E2123BE", "Löschzug 4", false, "LZ 4"));
		$this->save(new Engine("A67C8A08-3BCD-6FA0-9BF4-491A5121EA7B", "Löschzug 5", false, "LZ 5"));
		$this->save(new Engine("6D9D8344-BE44-BFD3-1B0F-72BE5E56571E", "Löschzug 6", false, "LZ 6"));
		$this->save(new Engine("C440BB6A-D8BF-3FAB-FC57-FAE475A1DBED", "Löschzug 7", false, "LZ 7"));
		$this->save(new Engine("1311075E-1260-2685-0822-8102BE480F32", "Löschzug 8", false, "LZ 8"));
		$this->save(new Engine("67CF2ADD-F5ED-3D43-FFF1-C504B8F39743", "Löschzug 9", false, "LZ 9"));
		$this->save(new Engine("ACCEC110-290E-6A65-A750-6AA93625D784", "Brandschutzzug", false, "BZ"));
		$this->save(new Engine("FEE13FE0-CDE3-AD5F-8A25-851467C12C26", "UG ÖEL", false, "UG ÖEL"));
		$this->save(new Engine("57D2CB43-F3CE-3837-4181-2FE60FDB9277", "Keine Zuordnung", false, "-"));	
	}
	
	
}