<?php
require_once "db_connect.php";
/*
create_table_eventtype ();

function insert_eventtype($uuid, $type, $isseries) {
	global $db;
	//$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO eventtype (uuid, type, isseries) VALUES (?, ?, ?)");
	$statement->bind_param('ssi', $uuid, $type, $isseries);
		
	$result = $statement->execute();

	if ($result) {
		// echo "New record created successfully<br>";
	    return $uuid;
	} else {
		//echo "Error: " . $statement . "<br>" . $db->error . "<br><br>";
		return false;
	}
}

function get_eventtype($uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM eventtype WHERE uuid = ?");
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

function get_eventtype_from_name($name) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM eventtype WHERE type = ?");
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

function get_eventtypes() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM eventtype ORDER BY type");
	
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

function create_table_eventtype() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE eventtype (
                          uuid CHARACTER(36) NOT NULL,
						  type VARCHAR(64) NOT NULL,
                          isseries BOOLEAN NOT NULL,
                          PRIMARY KEY  (uuid)
                          )");
	
	$result = $statement->execute();

	if ($result) {
		// echo "Table created<br>";	    
	    
	    insert_eventtype ("325FF3CA-62BE-3F3E-88D8-A1C932BE600B", "Theaterwache", true );
	    insert_eventtype ("C5503C1D-E08C-4850-27CB-563302EC9318", "Theaterwache Schüler", true  );
		insert_eventtype ("00155A58-8720-29CF-42F0-713895C7BFDA", "Theaterwache Prantlgarten", true  );
		insert_eventtype ("84D42DC4-0BCD-3DD4-C4D4-7D68CAB559D0", "Residenzwache", false );
		insert_eventtype ("5B3243FF-2D65-A0E6-E92D-0B2B8DC38D32", "Rathauswache", false );
		insert_eventtype ("7C5B9E95-0EC0-DFD5-ED7E-9A736BAD0AD1", "Wache Sparkassenarena", false );
		insert_eventtype ("E438FF03-C5FA-EB29-59F4-CD76B11054C3", "Burgwache", false );
		insert_eventtype ("D5156566-8F0D-FC74-983E-92B82A5F2917", "Dultwache", true  );
		insert_eventtype ("38579dc9-3c32-11e9-a62d-0800272f1758", "Wache Niederbayern-Schau", true  );
		insert_eventtype ("25d9b1d7-3c32-11e9-a62d-0800272f1758", "Wache Landshuter Hochzeit", true  );
		insert_eventtype ("3D79FB4B-C85E-DF81-863B-60C0DB7601C9", "Sonstige Wache", false );
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}
*/