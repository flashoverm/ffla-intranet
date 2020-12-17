<?php 
require_once REPOSITORIES_PATH . "/db_connect.php";

require_once LIBRARY_PATH . "/util.php";

/*
//Restrictions
define("FILEADMIN", "FILEADMIN");

define("FFADMINISTRATION", "FFADMINISTRATION");

define("ENGINEHYDRANTMANANGER", "ENGINEHYDRANTMANANGER");
define("HYDRANTADMINISTRATOR", "HYDRANTADMINISTRATOR");

define("PORTALADMIN", "PORTALADMIN");
define("EDITUSER", "EDITUSER");

define("EVENTPARTICIPENT", "EVENTPARTICIPENT");
define("EVENTMANAGER", "EVENTMANAGER");
define("EVENTADMIN", "EVENTADMIN");

create_table_user_privilege();
create_table_privilege();

function init_privileges(){
	insert_privilege('5873791F-68EC-159D-EF91-3288F02EF1D2', FILEADMIN);
	insert_privilege('10590E6B-FC09-49B3-6A35-53759D10D1FC', FFADMINISTRATION);
	insert_privilege('6B296269-6280-EAC5-B5F3-4A95C3FA7656', ENGINEHYDRANTMANANGER);
	insert_privilege('2B3DE880-1EB7-C9A1-C533-BD90F773FDBA', HYDRANTADMINISTRATOR);
	insert_privilege('EE50BFB0-B4B0-2AE2-AAE4-2FB6EE9DA558', PORTALADMIN);
	insert_privilege('231C64FA-24F4-CDA4-60FE-B211A364D5AE', EDITUSER, true);
	insert_privilege('C4E19AFC-14CA-9714-B0E6-B1354EC0571C', EVENTPARTICIPENT, true);
	insert_privilege('26F7145B-826A-F731-4F59-E435B2E94F81', EVENTMANAGER);
	insert_privilege('9941EE1E-6E61-0656-E72B-18A4EE48633C', EVENTADMIN);
}

function insert_privilege($uuid, $privilege, $is_default = false){
	global $db;
		
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("INSERT INTO privilege (uuid, privilege, is_default) VALUES (?, ?, ?)");
	$statement->bind_param('ssi', $uuid, $privilege_name, $is_default);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function add_privilege_to_user_by_name($user_uuid, $privilege){
	global $db;
	
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("INSERT INTO user_privilege (user, privilege) VALUES (?, (SELECT uuid FROM privilege WHERE privilege = ?))");
	$statement->bind_param('ss', $user_uuid, $privilege_name);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function add_privilege_to_user($user_uuid, $privilege_uuid){
	global $db;
		
	$statement = $db->prepare("INSERT INTO user_privilege (user, privilege) VALUES (?, ?)");
	$statement->bind_param('ss', $user_uuid, $privilege_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function get_privilege($privilege_uuid){
	global $db;
	
	$statement = $db->prepare("SELECT * FROM privilege WHERE uuid = ? ");
	$statement->bind_param('s', $privilege_uuid);
	
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

function get_privilege_by_name($privilege){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM privilege WHERE privilege = ? ");
	$statement->bind_param('s', $privilege);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			while ( $date = $result->fetch_object() ) {
				$data [] = $date;
			}
			$result->free ();
		}
	}
	return $data;
}

function get_all_privileges(){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM privilege ORDER BY privilege");
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			while ( $date = $result->fetch_object() ) {
				$data [] = $date;
			}
			$result->free ();
		}
	}
	return $data;
}

function get_users_privileges($user_uuid){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT privilege FROM user_privilege WHERE user = ? ");
	$statement->bind_param('s', $user_uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			while ( $date = $result->fetch_object() ) {
				$data [] = $date->privilege;
			}
			$result->free ();
		}
	}
	return $data;
}

function user_has_privilege($user_uuid, $privilege){
	global $db;
	
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("SELECT * FROM user_privilege, privilege WHERE user = ? AND privilege.uuid = user_privilege.privilege AND privilege.privilege = ?");
	$statement->bind_param('ss', $user_uuid, $privilege_name);

	if ($statement->execute()) {
		$result = $statement->get_result();
		if (mysqli_num_rows ( $result )) {
			return true;
		}
	}
	return false;
}

function current_user_has_privilege($privilege){
	if(userLoggedIn()){
		$privilege_name = strtoupper($privilege);
		
		return user_has_privilege($_SESSION ['intranet_userid'], $privilege_name);
	}
	return false;
}

function remove_privilege_from_user($user_uuid, $privilege_uuid){
	global $db;
		
	$statement = $db->prepare("DELETE FROM user_privilege WHERE user = ? AND privilege = ?");
	$statement->bind_param('ss', $user_uuid, $privilege_uuid);
		
	$result = $statement->execute();

	if ($result) {
		return true;
	}
	// echo "Error: " . $query . "<br>" . $db->error;
	return false;
}

function remove_privileges_from_user($user_uuid){
	global $db;
	$statement = $db->prepare("DELETE FROM user_privilege WHERE user = ?");
	$statement->bind_param('s', $user_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	}
	// echo "Error: " . $query . "<br>" . $db->error;
	return false;
}

function create_table_user_privilege() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE user_privilege (
						  privilege CHARACTER(36) NOT NULL,
						  user CHARACTER(36) NOT NULL,
                          PRIMARY KEY (privilege, user),
						  FOREIGN KEY (privilege) REFERENCES privilege(uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid)
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

function create_table_privilege() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE privilege (
						  uuid CHAR(36) NOT NULL,
						  privilege VARCHAR(32) NOT NULL,
						  is_default BOOLEAN NOT NULL,
                          PRIMARY KEY (uuid)
                          )");
	
	$result = $statement->execute();
	
	if ($result) {
		init_privileges();
		// echo "Table created<br>";
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}


?>
*/