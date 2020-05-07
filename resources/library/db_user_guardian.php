<?php  

//TODO rename manager to event manager, rename admin to eventadmin

function insert_manager($firstname, $lastname, $email, $password, $engine_uuid) {
	global $db;
	$uuid = getGUID ();
	$pwhash = password_hash ( $password, PASSWORD_DEFAULT );
	$mail = strtolower($email);
		
	$statement = $db->prepare("INSERT INTO user (uuid, firstname, lastname, email, password, engine, loginenabled, available) 
		VALUES (?, ?, ?, ?, ?, ?, TRUE, TRUE)");
	$statement->bind_param('ssssss', $uuid, $firstname, $lastname, $mail, $pwhash, $engine_uuid);
	
	$result = $statement->execute();

	if ($result) {
		add_privilege_to_user($uuid, EVENTMANAGER);
		// echo "New record created successfully";
	    return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function insert_admin($firstname, $lastname, $email, $password, $engine_uuid) {
	global $db;
	$uuid = getGUID ();
	$pwhash = password_hash ( $password, PASSWORD_DEFAULT );
	$mail = strtolower($email);
	
	$statement = $db->prepare("INSERT INTO user (uuid, firstname, lastname, email, password, engine, loginenabled, available)
		VALUES (?, ?, ?, ?, ?, ?, TRUE, TRUE)");
	$statement->bind_param('ssssss', $uuid, $firstname, $lastname, $mail, $pwhash, $engine_uuid);
	
	$result = $statement->execute();

	if ($result) {
		add_privilege_to_user($uuid, EVENTADMIN);
		add_privilege_to_user($uuid, EVENTMANAGER);
		
		// echo "New record created successfully";
	    return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}


function get_all_manager() {
	global $db;
	$data = array ();
	
	$right = '%' . EVENTMANAGER . '%';
	$statement = $db->prepare("SELECT * FROM user WHERE rights LIKE ?");
	$statement->bind_param('s', $right);
	
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

function get_manager_of_engine($engine_uuid) {
	global $db;
	$data = array ();
	
	$right = '%' . EVENTMANAGER . '%';
	$statement = $db->prepare("SELECT * FROM user WHERE rights LIKE ? AND engine = ?");
	$statement->bind_param('ss', $right, $engine_uuid);
	
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

function get_manager_except_engine_and_creator($engine_uuid, $creator_uuid){
	global $db;
	$data = array ();
	
	$privilege = EVENTMANAGER;
	
	$statement = $db->prepare("SELECT *
		FROM user
		WHERE user.uuid = privilege_user.user
		AND NOT engine = ?
		AND NOT uuid = ?
		AND privilege_user.privilege = ?");
	$statement->bind_param('sss', $engine_uuid, $creator_uuid, $privilege);
	
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

function is_manager_of($user_uuid, $engine_uuid){
	global $db;
	
	$privilege = EVENTMANAGER;
	
	$statement = $db->prepare("SELECT *
		FROM user, privilege_user
		WHERE user.uuid = privilege_user.user
		AND user.uuid = ?
		AND user.engine = ?
		AND privilege_user.privilege = ?");
	$statement->bind_param('sss', $user_uuid, $engine_uuid, $privilege);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			return true;
		}
	}
	return false;
}

//TODO rename to deny events
function hide_user($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET available = FALSE WHERE uuid= ?");
	$statement->bind_param('s', $uuid);
	
	$result = $statement->execute();

	if ($result) {
		// echo "Record ".$uuid." updated successfully";
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

//TODO rename to allow events 
function show_user($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET available = TRUE WHERE uuid= ?");
	$statement->bind_param('s', $uuid);
	
	$result = $statement->execute();

	if ($result) {
		// echo "Record ".$uuid." updated successfully";
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

?>