<?php
require_once "db_connect.php";

require_once LIBRARY_PATH . "/class/constants/ConfirmationStates.php";
/*
create_table_confirmation();

function create_confirmation($date, $start_time, $end_time, $description, $user){
	global $db;
	
	$uuid = getGUID ();
	$state = ConfirmationState::Open;
	
	$statement = $db->prepare("INSERT INTO confirmation (uuid, date, start_time, end_time, description, user, state) 
		VALUES (?, ?, ?, ?, ?, ?, ?)");
	$statement->bind_param('ssssssi', $uuid, $date, $start_time, $end_time, $description, $user, $state);
	
	$result = $statement->execute();
	
	if ($result) {
		return $uuid;
	} else {
		echo "Error: " . "<br>" . $db->error;
		return false;
	}
}

function get_confirmation($uuid) {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM confirmation WHERE uuid = ?");
	$statement->bind_param('s', $uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			return $data;
		}
	}
	return $data;
}

function get_confirmations_with_state($state) {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM confirmation WHERE state = ?");
	$statement->bind_param('i', $state);
	
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

function get_confirmations_of_user_with_state($user_uuid, $state){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM confirmation WHERE user = ? AND state = ?");
	$statement->bind_param('si', $user_uuid, $state);
	
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
*/
/*
function update_confirmations($uuid, $date, $start_time, $end_time, $description) {
	global $db;
	
	$state = ConfirmationState::Open;
	
	$statement = $db->prepare("UPDATE confirmation 
		SET date = ?, start_time = ?, end_time = ?, description = ?, state = ?, reason = NULL
		WHERE uuid= ?");
	$statement->bind_param('ssssis', $date, $start_time, $end_time, $description, $state, $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}
/*
function accept_confirmation($uuid, $advisor) {
	global $db;
	
	$state = ConfirmationState::Accepted;
	
	$statement = $db->prepare("UPDATE confirmation SET state = ?, reason = ?, last_advisor = ? WHERE uuid= ?");
	$statement->bind_param('isss', $state, $reason, $advisor, $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function decline_confirmation($uuid, $reason, $advisor) {
	global $db;
	
	$state = ConfirmationState::Declined;
	
	$statement = $db->prepare("UPDATE confirmation SET state = ?, reason = ?, last_advisor = ? WHERE uuid= ?");
	$statement->bind_param('isss', $state, $reason, $advisor, $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function delete_confirmation($uuid) {
	global $db;

	$statement = $db->prepare("DELETE FROM confirmation WHERE uuid= ?");
	$statement->bind_param('s', $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function create_table_confirmation() {
    global $db;
    
    $statement = $db->prepare("CREATE TABLE confirmation (
						  uuid CHARACTER(36) NOT NULL,
                          date DATE  NOT NULL,
                          start_time TIME NOT NULL,
                          end_time TIME NOT NULL,
                          description VARCHAR(255) NOT NULL,
						  user CHARACTER(36) NOT NULL,
						  state TINYINT NOT NULL, 
						  reason VARCHAR(255),
						  last_advisor CHARACTER(36),
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid),
						  FOREIGN KEY (last_advisor) REFERENCES user(uuid)
                          )");
    $result = $statement->execute();
    
    if ($result) {
        // echo "Table created<br>";
        return true;
    } else {
        // echo "Error: " . $db->error . "<br><br>";
        return false;
    }
}
*/