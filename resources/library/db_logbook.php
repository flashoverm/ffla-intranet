<?php
require_once LIBRARY_PATH . "/db_connect.php";
require_once LIBRARY_PATH . "/logbook_controller.php";

create_table_logbook();

function insert_log($action_id, $object_uuid, $message = null){
	global $db, $logbookActions;
		
	$uuid = getGUID ();
	$user_uuid = NULL;
	if(isset($_SESSION ['intranet_userid'])){
		$user_uuid = $_SESSION ['intranet_userid'];
	}
	
	if($message == null){
		$logmessage = logbookEnry($action_id, $object_uuid);
		if($logmessage == null){
			$logmessage = "Log-Eintrag fehlgeschlagen für: " . $logbookActions[$action_id];
		}
	} else {
		$logmessage = $message;
	}
	
	$objects = $object_uuid;
	if(is_array($object_uuid)){
		$objects = implode(",", $object_uuid);
	}
	
	$statement = $db->prepare("INSERT INTO logbook (uuid, timestamp, action, object, user, message)
		VALUES(?, NOW(), ?, ?, ?, ?)");
	$statement->bind_param('sisss', $uuid, $action_id, $objects, $user_uuid, $logmessage);
	
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