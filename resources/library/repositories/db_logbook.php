<?php
require_once REPOSITORIES_PATH . "/db_connect.php";
require_once LIBRARY_PATH . "/class/LogbookEntry.php";

create_table_logbook();

function insert_logbook_entry(LogbookEntry $entry){
	global $db;
	
	$objects = $entry->objects;
	if(is_array($objects)){
		$objects = implode(",", $objects);
	}
	
	$statement = $db->prepare("INSERT INTO logbook (uuid, timestamp, action, object, user, message)
		VALUES(?, ?, ?, ?, ?, ?)");
	$statement->bind_param('ssisss', $entry->uuid, $entry->timestamp, $entry->actionId, $objects, $entry->user, $entry->message);
	
	$result = $statement->execute();
	
	if ($result) {
		return $entry->uuid;
	} else {
		//echo "Error: " . "<br>" . $db->error;
		return false;
	}
}
	

function insert_log($action_id, $object_uuid, $message){
	global $db;
		
	$uuid = getGUID ();
	$user_uuid = NULL;
	if(isset($_SESSION ['intranet_userid'])){
		$user_uuid = $_SESSION ['intranet_userid'];
	}
	
	$objects = $object_uuid;
	if(is_array($object_uuid)){
		$objects = implode(",", $object_uuid);
	}
	
	$statement = $db->prepare("INSERT INTO logbook (uuid, timestamp, action, object, user, message)
		VALUES(?, NOW(), ?, ?, ?, ?)");
	$statement->bind_param('sisss', $uuid, $action_id, $objects, $user_uuid, $message);
	
	$result = $statement->execute();
	
	if ($result) {
		return $uuid;
	} else {
		//echo "Error: " . "<br>" . $db->error;
		return false;
	}
}

function get_logbook(){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM logbook ORDER BY timestamp DESC");
	
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

function get_logbook_page($page, $resultSize = 20) {
	global $db;
	$data = array ();
	
	$fromRowNumber = (($page-1)*$resultSize)+1;
	$toRowNumber = $fromRowNumber+$resultSize;
	
	$db->query("SET @row_number = 0;");
	
	$statement = $db->prepare("
		SELECT  *
		FROM ( SELECT *, (@row_number:=@row_number + 1) AS RowNum FROM logbook ORDER BY timestamp DESC) AS Data
		WHERE   RowNum >= ? AND RowNum < ?
		ORDER BY RowNum");
	$statement->bind_param('ii', $fromRowNumber, $toRowNumber);
	
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

function get_logbook_count() {
	global $db;
	
	$statement = $db->prepare("SELECT count(*) AS count FROM logbook");
	
	if ($statement->execute()) {
		$result = $statement->get_result()->fetch_object()->count;
		return $result;
	} else {
		//echo "Error: " . $statement . "<br>" . $db->error . "<br><br>";
		return false;
	}
}

function clear_logbook() {
	global $db;
	
	$statement = $db->prepare("DELETE FROM logbook");
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		//echo "Error: " . $statement . "<br>" . $db->error . "<br><br>";
		return false;
	}
}

function create_table_logbook() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE logbook (
                          uuid CHARACTER(36) NOT NULL,
						  timestamp DATETIME NOT NULL,
						  action SMALLINT NOT NULL,
						  object VARCHAR(255),
						  user CHARACTER(36),
						  message CHARACTER(255),
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid)
                          )");
	
	$result = $statement->execute();

	if ($result) {
		return true;
	} else {
		return false;
	}
}