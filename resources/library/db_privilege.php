<?php 
require_once LIBRARY_PATH . "/db_connect.php";

create_table_privilege();
create_table_privilege_user();

function create_privilege($privilege){
	global $db;
	
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("INSERT INTO privilege (name) VALUES (?)");
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
	
	$statement = $db->prepare("INSERT INTO privilege_user (user, privilege) VALUES (?, ?)");
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
	
	$statement = $db->prepare("SELECT * FROM privilege ORDER BY name");
	
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

function get_users_privileges($user_uuid){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM privilege_user WHERE user = ? ");
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

function user_has_privilege($user_uuid, $privilege){
	global $db;
	
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("SELECT * FROM privilege_user WHERE user = ? AND privilege = ?");
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
	if(isset ($_SESSION ['guardian_userid'])){
		$privilege_name = strtoupper($privilege);
		
		return user_has_privilege($_SESSION ['guardian_userid'], $privilege_name);
	}
	return false;
}

function remove_privilege_from_user($user_uuid, $privilege){
	global $db;
	
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("DELETE FROM privilege_user WHERE user = ? AND privilege = ?");
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
	$statement = $db->prepare("DELETE FROM privilege_user WHERE user = ?");
	$statement->bind_param('s', $user_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	}
	// echo "Error: " . $query . "<br>" . $db->error;
	return false;
}


function create_table_privilege() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE privilege (
						  name VARCHAR(32) NOT NULL,
                          PRIMARY KEY (name)
                          )");
	echo  $db->error;
	$result = $statement->execute();
	
	if ($result) {
		create_privilege(EVENTMANAGER);
		create_privilege(EVENTADMIN);
		create_privilege(PORTALADMIN);
		// echo "Table created<br>";
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}

function create_table_privilege_user() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE privilege_user (
						  privilege VARCHAR(32) NOT NULL,
						  user CHARACTER(36) NOT NULL,
                          PRIMARY KEY (privilege, user),
						  FOREIGN KEY (privilege) REFERENCES privilege(name),
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


?>