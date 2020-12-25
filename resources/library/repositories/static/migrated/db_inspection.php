<?php
require_once "db_connect.php";
/*
create_table_inspection ();
create_table_hydrant_inspection ();


function insert_inspection($inspection) {
	global $db;
	$uuid = getUuid ();
	
	$statement = $db->prepare("INSERT INTO inspection (uuid, date, vehicle, name, notes, engine) VALUES (?, ?, ?, ?, ?, ?)");
	$statement->bind_param('ssssss', 
	    $uuid, 
	    $inspection->date, 
	    $inspection->vehicle, 
	    $inspection->name, 
	    $inspection->notes, 
	    $inspection->engine);
	
	$result = $statement->execute();

	if ($result) {	 
	    $inspection->uuid = $uuid;
	    
	    foreach($inspection->hydrants as $hydrant){
	        insert_inspection_hydrant($inspection->uuid, $hydrant);
	    }
	    return $uuid;
	} else {
		 echo "Error: " . "<br>" . $db->error . "<br><br>";
		 return false;
	}
}

function insert_inspection_hydrant($inspection_uuid, $hydrant) {
    global $db;
    
    $criteria = json_encode($hydrant->criteria);
    
    $statement = $db->prepare("INSERT INTO hydrant_inspection (inspection, hydrant, idx, type, criteria) VALUES (?, ?, ?, ?, ?)");
    $statement->bind_param('sssss', $inspection_uuid, $hydrant->uuid, $hydrant->idx, $hydrant->type, $criteria);
    
    $result = $statement->execute();
    
    if ($result) {
        return true;
    } else {
        echo "Error: " . "<br>" . $db->error . "<br><br>";
        return false;
    }
}

function get_inspection($uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM inspection WHERE uuid = ?");
	$statement->bind_param('s', $uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
            return build_inspection_object($data);
		}
	}
	return false;
}

function get_inspections_of_engine($engine_uuid){
    global $db;
    
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM inspection WHERE engine = ?");
    $statement->bind_param('s', $engine_uuid);
    
    if ($statement->execute()) {
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            while ( $date = $result->fetch_object () ) {
                $data [] = build_inspection_object($date);
            }
            $result->free ();
        }
    }
    return $data;
}

function get_inspections(){
    global $db;
    
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM inspection");
    
    if ($statement->execute()) {
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            while ( $date = $result->fetch_object () ) {
                $data [] = build_inspection_object($date);
            }
            $result->free ();
        }
    }
    return $data;
}

function get_inspection_hydrants($inspection) {
	global $db, $hydrantDAO;
    
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM hydrant_inspection WHERE inspection = ? ORDER BY idx");
    $statement->bind_param('s', $inspection->uuid);
    
    if ($statement->execute()) {
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            while ( $date = $result->fetch_object () ) {
            	$hydrant = new Hydrant($hydrantDAO->getHydrantsByUuid($date->hydrant)->hy, $date->idx, $date->type);
                $hydrant->setCriteria($date->criteria);
                $hydrant->uuid = $date->hydrant;
                $inspection->addHydrant($hydrant);
            }
            $result->free ();
        }
    }
    return $data;
}

function get_inspection_hydrant($inspection_uuid, $hydrant_uuid){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM hydrant_inspection WHERE inspection = ? AND hydrant = ?");
    $statement->bind_param('ss', $inspection_uuid, $hydrant_uuid);
    
    if ($statement->execute()) {
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            $data = $result->fetch_object ();
            $result->free ();
            return $data;
        }
    }
    return false;
}

function get_candidates_of_engine($engine_uuid){
    global $db;
    
    $data = array ();
    
    $statement = $db->prepare(
        "SELECT *
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
    
    echo $db->error;
            
    $statement->bind_param('s', $engine_uuid);
    
    if ($statement->execute()) {
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            while ( $date = $result->fetch_object () ) {
                $data [] = $date;
            }
            $result->free ();
        }
    }
    return $data;
}

function update_inspection($inspection){
    global $db;
    
    $statement = $db->prepare("UPDATE inspection
		SET date = ?, vehicle = ?, name = ?, notes = ?, engine = ?
		WHERE uuid = ?");
    $statement->bind_param('ssssss', $inspection->date, $inspection->vehicle, $inspection->name, $inspection->notes, $inspection->engine, $inspection->uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        
        delete_inspection_hydrants($inspection->uuid);
        
        foreach($inspection->hydrants as $hydrant){
            insert_inspection_hydrant($inspection->uuid, $hydrant);
        }
        
        return true;
    } else {
        echo "Error: " . $db->error;
        return false;
    }
}

function delete_inspection($inspection_uuid){
    global $db;
    
    delete_inspection_hydrants($inspection_uuid);
    
    $statement = $db->prepare("DELETE FROM inspection WHERE uuid = ?");
    $statement->bind_param('s', $inspection_uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        return true;
    } else {
         echo "Error: " . "<br>" . $db->error;
        return false;
    }
}

function delete_inspection_hydrants($inspection_uuid){
    global $db;
    
    $statement = $db->prepare("DELETE FROM hydrant_inspection WHERE inspection = ?");
    $statement->bind_param('s', $inspection_uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        return true;
    } else {
        // echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function build_inspection_object($data){
    $inspection = new HydrantInspection($data->date, $data->name, $data->vehicle, $data->notes);
    $inspection->uuid = $data->uuid;
    $inspection->engine = $data->engine;
    get_inspection_hydrants($inspection);
    return $inspection;
}

function create_table_inspection() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE inspection (
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
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}

function create_table_hydrant_inspection() {
    global $db;
    
    $statement = $db->prepare("CREATE TABLE hydrant_inspection (
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
    } else {
        // echo "Error: " . $db->error . "<br><br>";
        return false;
    }
}
*/
?>