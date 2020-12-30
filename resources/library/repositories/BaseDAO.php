<?php 

require_once __DIR__ . "/../../bootstrap.php";

//use Doctrine\ORM\EntityManager;

abstract class BaseDAO {
	
	protected $db;
	
	function __construct() {
		global $config;

		$this->db = new PDO(
				'mysql:host=' . $config ['db'] ['host'] . ';dbname=' . $config ['db'] ['dbname'] . ";charset=utf8", 
				$config ['db'] ['username'], 
				$config ['db'] ['password']);
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
	
	protected function handleResult($statement, $returnAlwaysArray, $callback = NULL){
		if($callback == NULL){
			$callback = "resultToObject";
		}
		
		$count = $statement->rowCount();
		
		if($returnAlwaysArray || $count > 1){
			
			$objects = array();
			while($row = $statement->fetch()) {
				$objects [] = call_user_func_array(array($this, $callback), array($row));
			}
			return $objects;
			
		} else if ( $count < 1){
			
			return false;
			
		} else {
			
			return call_user_func_array(array($this, $callback), array($statement->fetch()));
		}
	}
	
	abstract protected function resultToObject($result);
	
	protected function uuidExists($uuid, $tableName){
		if($uuid == NULL){
			return false;
		}
		$statement = $this->db->prepare("SELECT * FROM " . $tableName . " WHERE uuid = ?");
		$statement->execute(array($uuid));   
		
		if ($statement->execute()) {
			return $this->handleResult($statement, false);
		}
		return false;
	}
	
	protected function getUuid() {
		if (function_exists ( 'com_create_guid' )) {
			return com_create_guid ();
		} else {
			mt_srand ( ( double ) microtime () * 10000 ); // optional for php 4.2.0 and up.
			$charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
			$hyphen = chr ( 45 ); // "-"
			$uuid = substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 );
			return $uuid;
		}
	}
}