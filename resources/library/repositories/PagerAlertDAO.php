<?php

require_once "BaseDAO.php";

class PagerAlertDAO extends BaseDAO{
    
    function __construct(PDO $pdo) {
        parent::__construct($pdo, "pageralert");
    }
    
    function getPagerAlerts($getParams){
        $query = "SELECT *
			FROM pageralert";
        
        return $this->executeQuery($query, null, $getParams);
    }

    /*
     * Init and helper methods
     */
    
    protected function resultToObject($result){
        $object = new PagerAlert();
        $object->setFid($result['fid']);
        $object->setLat($result['lat']);
        $object->setLng($result['lng']);
        $object->setAlerted($result['alerted']);
        $object->setManufacturer($result['manufacturer']);
        return $object;
    }
    
    protected function createTable(){
        $statement = $this->db->prepare("CREATE TABLE pageralert (
						  fid INTEGER NOT NULL,
                          lat DECIMAL(10, 8) NOT NULL,
                          lng DECIMAL(10, 8) NOT NULL,
                          alerted BOOL NOT NULL default 0,
                          manufacturer VARCHAR(96),
                          PRIMARY KEY  (fid)
                          )");
        
        $statement = $this->db->prepare(
            "CREATE TABLE `pageralert` (
                `fid` int(2) DEFAULT NULL,
                `id` int(2) DEFAULT NULL,
                `city` varchar(9) DEFAULT NULL,
                `street` varchar(16) DEFAULT NULL,
                `housenr` varchar(2) DEFAULT NULL,
                `manufacturer` varchar(8) DEFAULT NULL,
                `alerted` int(1) DEFAULT NULL,
                `notes` varchar(15) DEFAULT NULL,
                `lat` varchar(18) DEFAULT NULL,
                `lng` varchar(18) DEFAULT NULL
                )"
            );
        
        $result = $statement->execute();
        
        if ($result) {
            return true;
        }
        return false;
    }

    
    
}