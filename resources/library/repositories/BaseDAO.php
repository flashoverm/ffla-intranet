<?php 

use Doctrine\DBAL\Platforms\Keywords\ReservedKeywordsValidator;

require_once __DIR__ . "/../../bootstrap.php";
require_once "util/ResultSet.php";

//use Doctrine\ORM\EntityManager;

abstract class BaseDAO {
	
	protected $db;
	protected $tableName;
	
	function __construct(PDO $pdo, $tableName) {
		$this->db = $pdo;
		$this->tableName = $tableName;
		$this->createTableIfNotPresent($tableName);
		$this->createTableIfNotPresent("search");
	}
	
	abstract protected function createTable();
	
	abstract protected function resultToObject($result);
	
	
	static function getPDO() : PDO {
		global $config;
		
		$db = new PDO(
				'mysql:host=' . $config ['db'] ['host'] . ';dbname=' . $config ['db'] ['dbname'] . ";charset=utf8",
				$config ['db'] ['username'],
				$config ['db'] ['password']);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		return $db;
	}

	
	function executeQuery(string $query, ?array $parameter, array $getParams = null){

		$execQuery = $query;
		
		$resultSet = new ResultSet();
		$resultSet->initializeFromGetParams($getParams);
					
		//Not search, do database pagination (default behaviour)
		if( ! $resultSet->isSearch()){
			
			//if order by -> remove current order by clause
			$orderBy = "";
			if($resultSet->isSorted()){
				$orderByPos = strpos(strtolower($execQuery), "order by");
				if($orderByPos !== false){
					$execQuery = substr($execQuery, 0, $orderByPos);
				}
				$orderBy .= " ORDER BY " . $resultSet->getSortedBy();
				if($resultSet->getDesc()){
					$orderBy .= " DESC";
				} else {
					$orderBy .= " ASC";
				}
			}
			
			$resultSet->setOverallSize($this->getQueryResultCount($query, $parameter));
			
			$this->db->query("SET @row_number = 0;");
			
			$fromPos = strpos($execQuery, "FROM");
			
			//Version for maridb < 10.2 with error in joined tables!
			//$rowNumQuery = substr_replace($execQuery, ", (@row_number:=@row_number + 1) AS RowNum FROM", $fromPos, strlen("FROM"));
			$rowNumQuery = substr_replace($execQuery, ", ROW_NUMBER() OVER ( " . $orderBy . " ) AS RowNum FROM", $fromPos, strlen("FROM"));
			
			
			
			$execQuery = "SELECT * FROM ( " . $rowNumQuery . " ) as Data WHERE RowNum >= ? AND RowNum < ? ORDER BY RowNum";
			
			$parameter[] = $resultSet->getFrom();
			$parameter[] = $resultSet->getTo();
		}
				
		//Otherwise apply no database pagination, get all results and do frontend pagination (and search etc.) 
		
		$statement = $this->db->prepare($execQuery);
		
		if ($statement->execute($parameter)) {
			$result = $this->handleResult($statement, true);
			
			$resultSet->setData($result);

			return $resultSet;
		}
		return false;
	}
	
	function getEntryCount(){
		$statement = $this->db->prepare("SELECT count(*) AS count FROM " . $this->tableName);
		
		if ($statement->execute()) {
			return $statement->fetchColumn();
		}
		return false;
	}
	
	protected function createTableIfNotPresent($tableName){
		$exists = $this->db->query("SELECT 1 FROM " . $tableName . " LIMIT 1");
		if(! $exists){
			$this->createTable();
		}
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
	
	protected function getQueryResultCount(string $query, ?array $parameter){
		$fromPos = strpos($query, "FROM");
		$countingQuery = substr_replace($query, "SELECT count(*) as count ", 0, $fromPos);
		
		$statement = $this->db->prepare($countingQuery);
		
		if ($statement->execute($parameter)) {
			return $statement->fetchColumn();
		}
		return false;
		
	}
	
	protected function uuidExists($uuid, $tableName){
		if($uuid == NULL){
			return false;
		}
		$statement = $this->db->prepare("SELECT * FROM " . $tableName . " WHERE uuid = ?");
		$statement->execute(array($uuid));   
		if ($statement->execute() && $statement->rowCount() > 0) {
			return true;
		}
		return false;
	}
	
	protected function generateUuid() {
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

	
	/*
	 * Search
	 */
	
	function index(Search $search){
		$saved = null;
		if($this->uuidExists($search->getUuid(), "search")){
			echo "Update search";
			$saved = $this->updateSearch($search);
		} else {
			$saved = $this->insertSearch($search);
		}
		if($saved != null){
			return $saved;
		}
		return false;
	}
	
	function searchByTable($search, $tableName){
		$statement = $this->db->prepare("SELECT * FROM search
			WHERE tableName = ? AND json LIKE '%?%'");
		
		if ($statement->execute(array($tableName, $search))) {
			return $this->handleResult($statement, true, "resultToSearchObject");
		}
		return false;
	}
	
	private function resultToSearchObject($result){
		$object = new Search();
		$object->setUuid($result['uuid']);
		$object->setTableName($result['tableName']);
		$object->setJson($result['json']);
		return $object;
	}
	
	private function insertSearch(Search $searchElement){
		$statement = $this->db->prepare("INSERT INTO search
			(uuid, tableName, json)
		VALUES (?, ?, ?)");
		
		$result = $statement->execute(array(
			$searchElement->getUuid(), $searchElement->getTableName(), $searchElement->getJson()
		));
		
		if ($result) {
			return $searchElement;
		}
		return false;
	}
	
	private function updateSearch(Search $search){

		$statement = $this->db->prepare("UPDATE search
		SET json = ?
		WHERE uuid = ? AND tableName = ?");
		
		$result = $statement->execute(array(
				$search->getJson(), $search->getUuid(), $search->getTableName()
		));
		
		if ($result) {
			return $search;
		}

		return false;
	}
	
	private function createSearchTable() {
		$statement = $this->db->prepare("CREATE TABLE search (
                          uuid CHARACTER(36) NOT NULL,
						  tableName VARCHAR(32) NOT NULL,
                          json TEXT NOT NULL,
                          PRIMARY KEY  (uuid, tableName)
                          )");
		
		$result = $statement->execute();
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
}