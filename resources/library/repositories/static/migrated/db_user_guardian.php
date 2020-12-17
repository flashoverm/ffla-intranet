<?php  
/*
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
*/
/*
function insert_eventadmin($firstname, $lastname, $email, $password, $engine_uuid) {
	$result = insert_user($firstname, $lastname, $email, $password, $engine_uuid, null, null);
	
	if ($result) {
		add_privilege_to_user_by_name($result->uuid, EVENTMANAGER);
		add_privilege_to_user_by_name($result->uuid, EVENTADMIN);
		
		return $result;
	}
	// echo "Error: " . $query . "<br>" . $db->error;
	return false;
}


function get_all_eventmanager() {
	return get_users_with_privilege_by_name(EVENTMANAGER);
}
*/
/*
function get_eventmanager_of_engine($engine_uuid) {
	global $db;
	$data = array ();
	
	$privilege  = EVENTMANAGER;
	
	$statement = $db->prepare("SELECT user.* 
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND user_privilege.privilege = privilege.uuid
		AND privilege.privilege = ? 
		AND engine = ?
		AND user.deleted = false");
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
*/
/*
function get_eventmanager_except_engine_and_creator($engine_uuid, $creator_uuid){
	global $db;
	$data = array ();
	
	$privilege = EVENTMANAGER;
	
	$statement = $db->prepare("SELECT user.*
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND user_privilege.privilege = privilege.uuid
		AND NOT engine = ?
		AND NOT user.uuid = ?
		AND privilege.privilege = ?
		AND user.deleted = false");
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
/*
function get_eventparticipent_of_engine($engine_uuid){
	global $db;
	$data = array ();
	
	$privilege  = EVENTPARTICIPENT;
	
	$statement = $db->prepare("SELECT user.*
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND user_privilege.privilege = privilege.uuid
		AND privilege.privilege = ?
		AND engine = ?
		AND user.deleted = false
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
*/
/*
function is_eventmanager_of($user_uuid, $engine_uuid){
	global $db;
	
	$privilege = EVENTMANAGER;
	
	$statement = $db->prepare("SELECT user.*
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND user_privilege.privilege = privilege.uuid
		AND user.uuid = ?
		AND user.engine = ?
		AND user.deleted = false
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
*/
/*
function is_allowed_to_edit_event($user_uuid, $event_uuid){
	if($user_uuid != NULL){
		
		if(user_has_privilege($user_uuid, EVENTADMIN)){
			return true;
		}
		
		if(user_has_privilege($user_uuid, FFADMINISTRATION)){
			return true;
		}
		
		$event = get_event($event_uuid);
		if(is_eventmanager_of($user_uuid, $event->engine)){
			return true;
		}
		
		if($event->creator == $user_uuid){
			return true;
		}
	}
	return false;

}

function is_user_allowed_to_edit_report($user_uuid, $report_uuid){
	global $userDAO;
	
	if($user_uuid != NULL){
		$user = $userDAO->getUserByUUID($user_uuid);
		
		if($user->hasPrivilegeByName(Privilege::EVENTADMIN)){
			return true;
		}
		
		if($user->hasPrivilegeByName(Privilege::FFADMINISTRATION)){
			return true;
		}
		
		$report = get_report($report_uuid);
		if(is_eventmanager_of($user_uuid, $report->engine)){
			return true;
		}
	}
	return false;
}

function is_allowed_to_edit_events($user_uuid){
	if($user_uuid != NULL){
		
		if(user_has_privilege($user_uuid, EVENTADMIN)){
			return true;
		}
		
		if(user_has_privilege($user_uuid, FFADMINISTRATION)){
			return true;
		}
		
		if(user_has_privilege($user_uuid, EVENTMANAGER)){
			return true;
		}
	}
	return false;
}
*/
?>