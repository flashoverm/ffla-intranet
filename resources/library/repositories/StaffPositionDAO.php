<?php

require_once "BaseDAO.php";

class StaffPositionDAO extends BaseDAO{
	
	function __construct(PDO $pdo) {
		parent::__construct($pdo, "staffposition");
	}
	
	function save(StaffPosition $staffPosition){
		$statement = $this->db->prepare("INSERT INTO staffposition (uuid, position, list_index) VALUES (?, ?, ?)");
		
		$result = $statement->execute(array($staffPosition->getUuid(), $staffPosition->getPosition(), $staffPosition->getListIndex()));
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	function getStaffPosition($uuid) {
		$statement = $this->db->prepare("SELECT * FROM staffposition WHERE uuid = ?");
		
		if ($statement->execute(array($uuid))) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	function getStaffPositions() {
		$statement = $this->db->prepare("SELECT * FROM staffposition ORDER BY list_index");
		
		if ($statement->execute()) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	
	/*
	 * Init and helper methods
	 */
	
	protected function resultToObject($result){
		$object = new StaffPosition($result['uuid'], $result['position'], $result['list_index']);
		return $object;
	}
	
	protected function createTable() {
		$statement = $this->db->prepare("CREATE TABLE staffposition (
                          uuid CHARACTER(36) NOT NULL,
						  position VARCHAR(64) NOT NULL,
                          list_index TINYINT NULL,
                          PRIMARY KEY  (uuid)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			$this->initializeStaffPositions();
			return true;
		}
		return false;
	}
	
	protected function initializeStaffPositions(){
	    $this->save(new StaffPosition("D7962C08-A1CE-ADB4-5FE2-AAF219E0BDE8", "Dienstgrad (Verbandsführer)", 10 ));
		$this->save(new StaffPosition("BE8BA2F1-11B0-F8DB-292D-8F054A797214", "Dienstgrad (Zugführer)", 20 ));
		$this->save(new StaffPosition("28F8486C-1F14-4293-6BB6-59A959281FE3", "Dienstgrad (Gruppenführer)", 30 ));
		$this->save(new StaffPosition("C6C83E5B-660D-33A5-3B45-B4B2E4F13F23", "Maschinist", 40 ));
		$this->save(new StaffPosition("22BEB994-A05A-0195-4512-ED05FC84AE9C", "Drehleitermaschinist", 50 ));
		$this->save(new StaffPosition("DAA45E2B-7691-3CF3-4D0D-0C1A39DD0003", "Atemschutzträger", 60 ));
		$this->save(new StaffPosition("9CB30C8D-9ABD-487E-3385-3957B0ECD560", "Wachmann/-frau", 70 ));
	}
}