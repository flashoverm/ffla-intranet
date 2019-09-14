<?php
require_once LIBRARY_PATH . "/db_connect.php";

create_table_engine ();

function insert_engine($uuid, $name, $isadministration) {
	global $db;
	//$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO engine (uuid, name, isadministration) VALUES (?, ?, ?)");
	$statement->bind_param('ssi', $uuid, $name, $isadministration);
	
	$result = $statement->execute();

	if ($result) {
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
		insert_engine ("9BEECEFA-56CF-A009-0059-99DAA5FA0D4E", "Verwaltung", true );
		insert_engine ("2BAA144B-F946-1524-E60E-7DD485FE1881", "Löschzug 1/2", false );
		insert_engine ("9704558C-9A89-A5B0-7CDE-0321A518DCB1", "Löschzug 3", false );
		insert_engine ("B0C263B5-6416-B8F5-B7A2-4ED57E2123BE", "Löschzug 4", false );
		insert_engine ("A67C8A08-3BCD-6FA0-9BF4-491A5121EA7B", "Löschzug 5", false );
		insert_engine ("6D9D8344-BE44-BFD3-1B0F-72BE5E56571E", "Löschzug 6", false );
		insert_engine ("C440BB6A-D8BF-3FAB-FC57-FAE475A1DBED", "Löschzug 7", false );
		insert_engine ("1311075E-1260-2685-0822-8102BE480F32", "Löschzug 8", false );
		insert_engine ("67CF2ADD-F5ED-3D43-FFF1-C504B8F39743", "Löschzug 9", false );
		insert_engine ("ACCEC110-290E-6A65-A750-6AA93625D784", "Brandschutzzug", false );
		insert_engine ("57D2CB43-F3CE-3837-4181-2FE60FDB9277", "Keine Zuordnung", false );
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}