<?php 

require_once __DIR__ . "/../../bootstrap.php";

//use Doctrine\ORM\EntityManager;

abstract class BaseDAO {
		
	const PAGE_PARAM = "page";
	const PAGESIZE_PARAM = "pageSize";
	
	protected $db;
	protected $tableName;
	
	function __construct(PDO $pdo, $tableName) {
		$this->db = $pdo;
		$this->tableName = $tableName;
		$this->createTableIfNotPresent();
	}
	
	static function getPDO() : PDO {
		global $config;
		
		$db = new PDO(
				'mysql:host=' . $config ['db'] ['host'] . ';dbname=' . $config ['db'] ['dbname'] . ";charset=utf8",
				$config ['db'] ['username'],
				$config ['db'] ['password']);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		return $db;
	}
	
	protected function createTableIfNotPresent(){
		$exists = $this->db->query("SELECT 1 FROM " . $this->tableName . " LIMIT 1");
		if(! $exists){
			$this->createTable();
		}
	}
	
	abstract protected function createTable();
		
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
	
	function executeQuery(string $query, ?array $parameter, array $getParams = null){
		
		$options = self::extractPaginationFromGET($getParams);
		$page = $options[self::PAGE_PARAM];
		$pagesize = $options[self::PAGESIZE_PARAM];
		
		$execQuery = $query;
		
		if($getParams != null){
			$this->db->query("SET @row_number = 0;");
			
			$fromRowNumber = (($page-1)*$pagesize)+1;
			$toRowNumber = $fromRowNumber+$pagesize;
			
			$fromPos = strpos($query, "FROM");
			$rowNumQuery = substr_replace($query, ", (@row_number:=@row_number + 1) AS RowNum FROM", $fromPos, strlen("FROM"));
			
			$execQuery = "SELECT * FROM ( " . $rowNumQuery . " ) as Data WHERE RowNum >= ? AND RowNum < ? ORDER BY RowNum";
			
			$parameter[] = $fromRowNumber;
			$parameter[] = $toRowNumber;
		}
		
		$statement = $this->db->prepare($execQuery);
	
		if ($statement->execute($parameter)) {
			return $this->handleResult($statement, true);
		}
		return false;
	}
	
	function getQueryResultCount(string $query, ?array $parameter){
		$fromPos = strpos($query, "FROM");
		$countingQuery = substr_replace($query, "SELECT count(*) FROM", 0, $fromPos);
		
		$statement = $this->db->prepare($countingQuery);
		
		if ($statement->execute($parameter)) {
			return $statement->fetchColumn();
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
	
	static function extractPaginationFromGET(array $getParams){
		$paginationOptions = [];
		
		if(isset($getParams[self::PAGE_PARAM])){
			$paginationOptions[self::PAGE_PARAM] = $getParams[self::PAGE_PARAM];
		} else {
			$paginationOptions[self::PAGE_PARAM] = 1;
		}
		
		if(isset($getParams[self::PAGESIZE_PARAM])){
			$paginationOptions[self::PAGESIZE_PARAM] = $getParams[self::PAGESIZE_PARAM];
		} else {
			$paginationOptions[self::PAGESIZE_PARAM] = 10;
		}
		
		return $paginationOptions;
	}
}