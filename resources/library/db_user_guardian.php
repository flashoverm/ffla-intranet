<?php  

function inset_participant_only($firstname, $lastname, $email, $engine_uuid){
	global $db;
	
	$uuid = getGUID ();
	$emailLower = strtolower($email);
	
	$statement = $db->prepare("INSERT INTO user (uuid, email, firstname, lastname, engine)
		VALUES (?, ?, ?, ?, ?)");
	$statement->bind_param('sssss', $uuid, $emailLower, $firstname, $lastname, $engine_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		add_privilege_to_user_by_name($uuid, EVENTPARTICIPENT);
		
		$statement = $db->prepare("SELECT * FROM user WHERE uuid = ?");
		$statement->bind_param('s', $uuid);
		$statement->execute();
		$data = $statement->get_result()->fetch_object ();
		return $data;
		
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function insert_eventmanager($firstname, $lastname, $email, $password, $engine_uuid) {
	$result = insert_user($firstname, $lastname, $email, $password, $engine_uuid);

	if ($result) {
		add_privilege_to_user_by_name($result->uuid, EVENTMANAGER);
		return $result;
	}
	// echo "Error: " . $query . "<br>" . $db->error;
	return false;
}

function insert_eventadmin($firstname, $lastname, $email, $password, $engine_uuid) {
	$result = insert_user($firstname, $lastname, $email, $password, $engine_uuid);
	
	if ($result) {
		add_privilege_to_user_by_name($result->uuid, EVENTMANAGER);
		add_privilege_to_user_by_name($result->uuid, EVENTADMIN);
		
		return $result;
	}
	// echo "Error: " . $query . "<br>" . $db->error;
	return false;
}

function get_all_eventmanager() {
	return get_users_with_privilege(EVENTMANAGER);
}

function get_eventmanager_of_engine($engine_uuid) {
	global $db;
	$data = array ();
	
	$privilege  = EVENTMANAGER;
	
	$statement = $db->prepare("SELECT * 
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND user_privilege.privilege = privilege.uuid
		AND privilege.privilege = ? 
		AND engine = ?");
	$statement->bind_param('ss', $privilege, $engine_uuid);
	
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

function get_eventmanager_except_engine_and_creator($engine_uuid, $creator_uuid){
	global $db;
	$data = array ();
	
	$privilege = EVENTMANAGER;
	
	$statement = $db->prepare("SELECT *
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND user_privilege.privilege = privilege.uuid
		AND NOT engine = ?
		AND NOT uuid = ?
		AND privilege.privilege = ?");
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

function get_eventparticipent_of_engine($engine_uuid){
	global $db;
	$data = array ();
	
	$privilege  = EVENTPARTICIPENT;
	
	$statement = $db->prepare("SELECT *
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND user_privilege.privilege = privilege.uuid
		AND privilege.privilege = ?
		AND engine = ?
 		ORDER BY lastname");
	$statement->bind_param('ss', $privilege, $engine_uuid);
	
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

function is_eventmanager_of($user_uuid, $engine_uuid){
	global $db;
	
	$privilege = EVENTMANAGER;
	
	$statement = $db->prepare("SELECT *
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND user_privilege.privilege = privilege.uuid
		AND user.uuid = ?
		AND user.engine = ?
		AND privilege.privilege = ?");
	$statement->bind_param('sss', $user_uuid, $engine_uuid, $privilege);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			return true;
		}
	}
	return false;
}

?>