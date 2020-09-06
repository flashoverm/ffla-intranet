<?php
require_once LIBRARY_PATH . "/db_connect.php";

create_table_logbook();

function insert_log($action_id, $object_uuid){
	global $db;
	
	$uuid = getGUID ();
	$user_uuid = $_SESSION ['intranet_userid'];
	
	$statement = $db->prepare("INSERT INTO event (uuid, timestamp, action, object, user)
		VALUES(?, NOW(), ?, ?, ?)");
	$statement->bind_param('siss', $uuid, $action_id, $object_uuid, $user_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return $uuid;
	} else {
		//echo "Error: " . "<br>" . $db->error;
		return false;
	}
}

function create_table_logbook() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE logbook (
                          uuid CHARACTER(36) NOT NULL,
						  timestamp DATETIME NOT NULL,
						  action SMALLINT NOT NULL,
						  object CHARACTER(36) NOT NULL,
						  user CHARACTER(36),
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid),
                          )");
	
	$result = $statement->execute();

	if ($result) {
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}