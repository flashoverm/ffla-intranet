<?php
require_once LIBRARY_PATH . "/db_connect.php";

create_table_staffposition ();

function insert_staffposition($uuid, $position, $list_index) {
	global $db;
	//$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO staffposition (uuid, position, list_index) VALUES (?, ?, ?)");
	$statement->bind_param('ssi', $uuid, $position, $list_index);
		
	$result = $statement->execute();
	
	if ($result) {
		// echo "New record created successfully<br>";
	    return $uuid;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error . "<br><br>";
		return false;
	}
}

function get_staffposition($uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM staffposition WHERE uuid = ?");
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

function get_staffpositions() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM staffposition ORDER BY list_index");
	
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

function create_table_staffposition() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE staffposition (
                          uuid CHARACTER(36) NOT NULL,
						  position VARCHAR(64) NOT NULL,
                          list_index TINYINT NULL,
                          PRIMARY KEY  (uuid)
                          )");
	
	$result = $statement->execute();

	if ($result) {
	    insert_staffposition ("BE8BA2F1-11B0-F8DB-292D-8F054A797214", "Dienstgrad (LM)", 0 );
	    insert_staffposition ("28F8486C-1F14-4293-6BB6-59A959281FE3", "Dienstgrad (HFM)", 1 );
	    insert_staffposition ("C6C83E5B-660D-33A5-3B45-B4B2E4F13F23", "Maschinist", 2 );
	    insert_staffposition ("22BEB994-A05A-0195-4512-ED05FC84AE9C", "Drehleitermaschinist", 3 );
	    insert_staffposition ("DAA45E2B-7691-3CF3-4D0D-0C1A39DD0003", "Atemschutzträger", 4 );
	    insert_staffposition ("9CB30C8D-9ABD-487E-3385-3957B0ECD560", "Wachmann", 5 );
	    
		return true;
	} else {
		return false;
	}
}