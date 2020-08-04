<?php 
require_once LIBRARY_PATH . "/db_connect.php";
require_once LIBRARY_PATH . "/util.php";


//Restrictions
define("FILEADMIN", "FILEADMIN");

define("FFADMINISTRATION", "FFADMINISTRATION");

define("ENGINEHYDRANTMANANGER", "ENGINEHYDRANTMANANGER");
define("HYDRANTADMINISTRATOR", "HYDRANTADMINISTRATOR");

define("PORTALADMIN", "PORTALADMIN");
define("CHANGEPASSWORD", "CHANGEPASSWORD");

define("EVENTPARTICIPENT", "EVENTPARTICIPENT");
define("EVENTMANAGER", "EVENTMANAGER");
define("EVENTADMIN", "EVENTADMIN");

create_table_user_privilege();
create_table_privilege();

function init_privileges(){
	insert_privilege(FILEADMIN);
	insert_privilege(FFADMINISTRATION);
	insert_privilege(ENGINEHYDRANTMANANGER);
	insert_privilege(HYDRANTADMINISTRATOR);
	insert_privilege(PORTALADMIN);
	insert_privilege(CHANGEPASSWORD);
	insert_privilege(EVENTPARTICIPENT);
	insert_privilege(EVENTMANAGER);
	insert_privilege(EVENTADMIN);
}

function insert_privilege($privilege){
	global $db;
	
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("INSERT INTO privilege (privilege) VALUES (?)");
	$statement->bind_param('s', $privilege_name);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function add_privilege_to_user($user_uuid, $privilege){
	global $db;
	
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("INSERT INTO user_privilege (user, privilege) VALUES (?, ?)");
	$statement->bind_param('ss', $user_uuid, $privilege_name);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function get_all_privileges(){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT privilege FROM privilege ORDER BY privilege");
	
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
	
	$statement = $db->prepare("SELECT * FROM user_privilege WHERE user = ? ");
	$statement->bind_param('s', $user_uuid);
	
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

function get_users_privileges_array($user_uuid){
	$users_privileges = get_users_privileges($user_uuid);
	$users_privliege_array = array();
	foreach($users_privileges as $priv){
		$users_privliege_array[] = $priv->privilege;
	}
	return $users_privliege_array;
}

function user_has_privilege($user_uuid, $privilege){
	global $db;
	
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("SELECT * FROM user_privilege WHERE user = ? AND privilege = ?");
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

function remove_privilege_from_user($user_uuid, $privilege){
	global $db;
	
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("DELETE FROM user_privilege WHERE user = ? AND privilege = ?");
	$statement->bind_param('ss', $user_uuid, $privilege_name);
		
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
						  privilege VARCHAR(32) NOT NULL,
						  user CHARACTER(36) NOT NULL,
                          PRIMARY KEY (privilege, user),
						  FOREIGN KEY (privilege) REFERENCES privilege(privilege),
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
						  privilege VARCHAR(32) NOT NULL,
                          PRIMARY KEY (privilege)
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