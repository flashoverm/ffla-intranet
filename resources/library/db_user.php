<?php
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/password.php";
require_once LIBRARY_PATH . "/mail.php";
require_once LIBRARY_PATH . "/db_connect.php";
require_once LIBRARY_PATH . "/db_user_guardian.php";
create_table_user ();
require_once LIBRARY_PATH . "/db_privilege.php";

function insert_user($firstname, $lastname, $email, $password, $engine_uuid, $employer_address, $employer_mail) {
	global $db;

	$uuid = getGUID ();
	$emailLower = strtolower($email);
	
	$statement = $db->prepare("INSERT INTO user (uuid, email, firstname, lastname, password, engine, locked, employer_address, employer_mail)
	VALUES (?, ?, ?, ?, ?, ?, FALSE, ?, ?)");
	$statement->bind_param('ssssssss', $uuid, $emailLower, $firstname, $lastname, $password, $engine_uuid, $employer_address, $employer_mail);
	
	$result = $statement->execute();
	
	if ($result) {
		$statement = $db->prepare("SELECT * FROM user WHERE uuid = ?");
		$statement->bind_param('s', $uuid);
		$statement->execute();
		$data = $statement->get_result()->fetch_object ();
		return $data;
		
	} else {
		//echo "Error: " . "<br>" . $db->error;
		return false;
	}
}

function get_users(){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user WHERE deleted = false ORDER BY email");
	
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

function get_unlocked_user() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user 
		WHERE locked = FALSE
		AND deleted = false
		ORDER BY lastname");
	
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

function get_users_with_privilege_by_name($privilege){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT user.* 
		FROM user, user_privilege, privilege
		WHERE user.uuid = user_privilege.user AND privilege.uuid = user_privilege.privilege 
		AND privilege.privilege = ?
		AND user.deleted = false");
	$statement->bind_param('s', $privilege);
	
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

function get_users_with_privilege($privilege_uuid){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT user.*
		FROM user, user_privilege 
		WHERE uuid = user_privilege.user AND privilege = ?
		AND user.deleted = false");
	$statement->bind_param('s', $privilege_uuid);
	
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

function get_users_of_engine($engine_uuid){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user 
		WHERE engine = ? 
		AND locked = FALSE 
		AND deleted = false 
		ORDER BY lastname");
	$statement->bind_param('s', $engine_uuid);
	
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

function get_deleted_users(){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user WHERE deleted = true ORDER BY email");
	
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

function get_user($uuid){
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE uuid = ?
		AND deleted = false");
	$statement->bind_param('s', $uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			return $data;
		}
	}
	return false;
}

function get_user_by_email($email){
	global $db;
	
	$emailLower = strtolower($email);
	
	$statement = $db->prepare("SELECT * FROM user WHERE email = ?");
	$statement->bind_param('s', $emailLower);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			return $data;
		}
	}
	return false;
}

function get_user_by_data($firstname, $lastname, $email, $engine_uuid){
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE firstname = ? AND lastname = ? AND email = ? AND engine = ? ");
	$statement->bind_param('ssss', $firstname, $lastname, $email, $engine_uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			return $data;
		}
	}
	return false;
}

function get_current_user_obj(){
	return get_user($_SESSION ['intranet_userid']);
}

function get_engine_of_user($user_uuid){
	global $db;
	
	$statement = $db->prepare("SELECT engine FROM user WHERE uuid = ?");
	$statement->bind_param('s', $user_uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_row ();
			$result->free ();
			return $data[0];
		}
	}
	return false;
}

function get_engine_obj_of_user($user_uuid){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM engine, user WHERE user.engine = engine.uuid AND user.uuid = ?");
    $statement->bind_param('s', $user_uuid);
    
    if ($statement->execute()) {
                
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            $data = $result->fetch_object();
            $result->free ();
            return $data;
        }
    }
    return false;
}

function email_in_use($email) {
	global $db;
	$mail = strtolower($email);
	
	$statement = $db->prepare("SELECT * FROM user WHERE email = ?");
	$statement->bind_param('s', $mail);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			return true;
		}
	}
	return false;
}

function update_user($uuid, $firstname, $lastname, $email, $engine, $employer_address, $employer_mail) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET firstname = ?, lastname = ?, email = ?, engine = ?, employer_address = ?, employer_mail = ? WHERE uuid= ?");
	$statement->bind_param('sssssss', $firstname, $lastname, $email, $engine, $employer_address, $employer_mail, $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return get_user($uuid);
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function lock_user($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET locked = TRUE WHERE uuid= ?");
	$statement->bind_param('s', $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function unlock_user($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET locked = FALSE WHERE uuid= ?");
	$statement->bind_param('s', $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function reset_password($uuid) {
	$password = random_password ();
	$pwhash = password_hash ( $password, PASSWORD_DEFAULT );
	
	global $db;
	
	$statement = $db->prepare("UPDATE user SET password = ? WHERE uuid = ?");
	$statement->bind_param('ss', $pwhash, $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return $password;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function change_password($uuid, $old_password, $new_passwort) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE uuid = ?");
	$statement->bind_param('s', $uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			if (password_verify ( $old_password, $data->password ) || $old_password == $data->password) {
				$pwhash = password_hash ( $new_passwort, PASSWORD_DEFAULT );
				
				$statement = $db->prepare("UPDATE user SET password = ? WHERE uuid = ?");
				$statement->bind_param('ss', $pwhash, $uuid);
				
				$result = $statement->execute();
				
				if ($result) {
					return true;
				} else {
					// echo "Error: " . $query . "<br>" . $db->error;
					return false;
				}
			}
		}
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
	return false;
}

function delete_user($user_uuid){
	global $db;
	
	$statement = $db->prepare("UPDATE user SET deleted = TRUE WHERE uuid= ?");
	$statement->bind_param('s', $user_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function undelete_user($user_uuid){
	global $db;
	
	$statement = $db->prepare("UPDATE user SET deleted = FALSE WHERE uuid= ?");
	$statement->bind_param('s', $user_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function create_table_user() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE user (
                          uuid CHARACTER(36) NOT NULL,
                          email VARCHAR(128) NOT NULL UNIQUE,
                          password VARCHAR(255),
						  firstname VARCHAR(64) NOT NULL,
                          lastname VARCHAR(64) NOT NULL,
						  engine CHARACTER(36) NOT NULL,
						  locked BOOLEAN NOT NULL,
						  deleted BOOLEAN NOT NULL,
						  employer_address VARCHAR(255),
						  employer_mail VARCHAR(255),
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}

function initialize_user(){

	$user = insert_eventadmin("Admin", "Admin", "admin@guardian.de", "admin", get_engine_from_name("Keine Zuordnung")->uuid);
	add_privilege_to_user_by_name($user->uuid, PORTALADMIN);
}

?>