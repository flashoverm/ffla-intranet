<?php
require_once LIBRARY_PATH . "/db_connect.php";

create_table_engine ();

function insert_engine($name, $isadministration) {
	global $db;
	$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO engine (uuid, name, isadministration) VALUES (?, ?, ?)");
	$statement->bind_param('ssi', $uuid, $name, $isadministration);
	
	$result = $statement->execute();

	if ($result) {
		 //echo "New record created successfully<br>";
		 return true;
	} else {
		 //echo "Error: " . $query . "<br>" . $db->error . "<br><br>";
		 return false;
	}
}

function get_engine($uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM engine WHERE uuid = ?");
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

function get_engine_from_name($name) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM engine WHERE name = ?");
	$statement->bind_param('s', $name);
	
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

function get_engines() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM engine ORDER BY name");
	
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

function get_administration() {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM engine WHERE isadministration =  TRUE");
	
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

function get_engines_without_administration() {
    global $db;
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM engine WHERE NOT isadministration =  FALSE ORDER BY name");
    
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

function is_administration($uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT isadministration FROM engine
		WHERE isadministration = TRUE AND engine.uuid = ?");
	$statement->bind_param('s', $uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_row ();
			$result->free ();
			return $data [0];
		}
	}
	return FALSE;
}

function create_table_engine() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE engine (
                          uuid CHARACTER(36) NOT NULL,
						  name VARCHAR(32) NOT NULL,
                          isadministration BOOLEAN NOT NULL,
                          PRIMARY KEY  (uuid)
                          )");
	
	$result = $statement->execute();

	if ($result) {
		insert_engine( "Verwaltung", true );
		insert_engine ( "Löschzug 1/2", false );
		insert_engine ( "Löschzug 3", false );
		insert_engine ( "Löschzug 4", false );
		insert_engine ( "Löschzug 5", false );
		insert_engine ( "Löschzug 6", false );
		insert_engine ( "Löschzug 7", false );
		insert_engine ( "Löschzug 8", false );
		insert_engine ( "Löschzug 9", false );
		insert_engine ( "Brandschutzzug", false );
		insert_engine ( "Keine Zuordnung", false );
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}