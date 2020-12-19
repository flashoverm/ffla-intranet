<?php
require_once "db_connect.php";

create_table_hydrant();

function insert_hydrant($hy, $fid, $lat, $lng, $street, $district, $type, $engine, $checkbyff, $operating) {
    global $db;
    
    $uuid = getGUID ();
        
        $statement = $db->prepare("INSERT INTO hydrant 
            (uuid, fid, hy, street, type, checkbyff, district, lat, lng, engine, cycle, operating)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 6, ?)");
        $statement->bind_param('siissisddsi', $uuid, $fid, $hy, $street, $type, $checkbyff, $district, $lat, $lng, $engine, $operating);
        
    
    $result = $statement->execute();
    
    if ($result) {
        return $uuid;
    } else {
        return false;
    }
}

function update_hydrant($uuid, $hy, $fid, $lat, $lng, $street, $district, $type, $engine, $checkbyff, $operating) {
    global $db;
    
    $statement = $db->prepare("UPDATE hydrant
		SET hy = ?, fid = ?, lat = ?, lng = ?, street = ?, district = ?, type = ?, engine = ?, checkbyff = ?, operating = ?
		WHERE uuid = ?");
    $statement->bind_param('iiddssssiis', $hy, $fid, $lat, $lng, $street, $district, $type, $engine, $checkbyff, $operating, $uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        return $uuid;
    } else {
        //echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function get_hydrants(){
	global $db;
	
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM hydrant");
	
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

function get_streets(){
    global $db;
    
    $data = array ();
    
    $statement = $db->prepare("SELECT DISTINCT street FROM hydrant ORDER BY street");
    if($statement){
    	if ($statement->execute()) {
    		$result = $statement->get_result();
    		
    		if (mysqli_num_rows ( $result )) {
    			while ( $date = $result->fetch_object () ) {
    				$data [] = $date->street;
    			}
    			$result->free ();
    		}
    	}
    }    
    return $data;
}

function get_districts(){
	global $db;
	
	$data = array ();
	
	$statement = $db->prepare("SELECT DISTINCT district FROM hydrant ORDER BY district");
	if($statement){
		if ($statement->execute()) {
			$result = $statement->get_result();
			
			if (mysqli_num_rows ( $result )) {
				while ( $date = $result->fetch_object () ) {
					$data [] = $date->district;
				}
				$result->free ();
			}
		}
	}
	return $data;
}

function get_hydrants_of_engine($engine_uuid){
    global $db;
    
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM hydrant WHERE engine = ? AND operating = TRUE");
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

function get_hydrants_of_street($street){
    global $db;
    
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM hydrant WHERE street = ? AND operating = TRUE");
    $statement->bind_param('s', $street);
    
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

function get_hydrant_by_fid($fid){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM hydrant WHERE fid = ?");
    $statement->bind_param('i', $fid);
    
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

function get_hydrant($hy){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM hydrant WHERE hy = ?");
    $statement->bind_param('i', $hy);
    
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

function get_hydrant_by_uuid($uuid){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM hydrant WHERE uuid = ?");
    $statement->bind_param('s', $uuid);
    
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

function is_hydrant_existing($hy){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM hydrant WHERE hy = ?");
    $statement->bind_param('i', $hy);
    
    if ($statement->execute()) {
    	$result = $statement->get_result();
    	
    	if (mysqli_num_rows ( $result )) {
            $result->free ();
            return true;
        }
    }
   
    return false;
}

function is_map_existing($hy){
    global $config;
    global $db;
        
    $statement = $db->prepare("SELECT * FROM hydrant WHERE map IS NOT NULL AND NOT map = '' AND hy = ?");
    $statement->bind_param('i', $hy);
    
    if ($statement->execute()) {
    	$result = $statement->get_result();
    	
    	if (mysqli_num_rows ( $result )) {
            $data = $result->fetch_object ();
            $result->free ();
            if(file_exists($config["paths"]["maps"] . $data->map)){
            	return true;
            }
            
        }
    }
    return false;
}

function save_map($hy, $map){
    global $db;
    
    $statement = $db->prepare("UPDATE hydrant SET map = ? WHERE hy= ?");
    $statement->bind_param('si', $map, $hy);
    
    $result = $statement->execute();
    
    if ($result) {
        // echo "Record ".$fid." updated successfully";
        return true;
    } else {
        // echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function create_table_hydrant() {
    global $db;
    
    $statement = $db->prepare("CREATE TABLE hydrant (
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
        // echo "Table created<br>";
        return true;
    } else {
        //echo "Error: " . $db->error . "<br><br>";
        return false;
    }
}

?>