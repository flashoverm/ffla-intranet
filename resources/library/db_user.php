<?php
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/password.php";
require_once LIBRARY_PATH . "/mail.php";
require_once LIBRARY_PATH . "/db_connect.php";
require_once LIBRARY_PATH . "/db_user_guardian.php";
create_table_user ();
require_once LIBRARY_PATH . "/db_privilege.php";

initialize_user();

function insert_user($firstname, $lastname, $email, $password, $engine_uuid) {
	global $db;

	$uuid = getGUID ();
	$emailLower = strtolower($email);
	
	$statement = $db->prepare("INSERT INTO user (uuid, email, firstname, lastname, password, engine, locked)
	VALUES (?, ?, ?, ?, ?, ?, FALSE)");
	$statement->bind_param('ssssss', $uuid, $emailLower, $firstname, $lastname, $password, $engine_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		add_privilege_to_user($uuid, EVENTPARTICIPENT);
		
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

function get_users(){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user ORDER BY email");
	
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
	
	$statement = $db->prepare("SELECT * FROM user WHERE locked = FALSE ORDER BY lastname");
	
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

function get_users_with_privilege($privilege){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user, user_privilege 
		WHERE uuid = user_privilege.user AND privilege = ?");
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

function get_users_of_engine($engine_uuid){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user WHERE engine = ? AND locked = FALSE ORDER BY lastname");
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

function get_user($uuid){
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE uuid = ?");
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
	
	$statement = $db->prepare("SELECT * FROM user WHERE firstname = ? AND lastname = ? AND email = ? AND engine = ?");
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

function check_password($email, $password) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE email = ?");
	$statement->bind_param('s', $email);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			if ($password == $data->password ) {
				return $data->uuid;
			}
			if (password_verify ( $password, $data->password )) {
				return $data->uuid;
			}
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

function is_locked($email){
	global $db;
	$mail = strtolower($email);
	
	$statement = $db->prepare("SELECT locked FROM user WHERE email = ?");
	$statement->bind_param('s', $mail);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_row ();
			$result->free ();
			return $data [0];
		}
	}
	return false;
}

function update_user($uuid, $firstname, $lastname, $email, $engine) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET firstname = ?, lastname = ?, email = ?, engine = ? WHERE uuid= ?");
	$statement->bind_param('sssss', $firstname, $lastname, $email, $engine, $uuid);
	
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
					// echo "Record ".$uuid." updated successfully";
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
	
	if(false){
		
		$user = insert_eventadmin("Admin", "Admin", "admin@guardian.de", "admin", get_engine_from_name("Keine Zuordnung")->uuid);
		add_privilege_to_user($user->uuid, PORTALADMIN);
		
		insert_user("FF Landshut", "FF Landshut", "fflandshut", "ffla112", get_engine_from_name("Keine Zuordnung")->uuid);
		
		$user = insert_user("Fileadmin", "Fileadmin", "fffiles", "ffla112", get_engine_from_name("Keine Zuordnung")->uuid);
		add_privilege_to_user($user->uuid, FILEADMIN);
		
		$user = insert_user("LZ 1/2", "LZ 1/2", "lz12", "daigfhrfd", get_engine_from_name("Löschzug 1/2")->uuid);
		add_privilege_to_user($user->uuid, ENGINEHYDRANTMANANGER);
		$user = insert_user("LZ 3", "LZ 3", "lz3", "ddfgohwsfh", get_engine_from_name("Löschzug 3")->uuid);
		add_privilege_to_user($user->uuid, ENGINEHYDRANTMANANGER);
		$user = insert_user("LZ 4", "LZ 4", "lz4", "sgfjgsksfn", get_engine_from_name("Löschzug 4")->uuid);
		add_privilege_to_user($user->uuid, ENGINEHYDRANTMANANGER);
		$user = insert_user("LZ 5", "LZ 5", "lz5", "xvhkgdjdaf", get_engine_from_name("Löschzug 5")->uuid);
		add_privilege_to_user($user->uuid, ENGINEHYDRANTMANANGER);
		$user = insert_user("LZ 6", "LZ 6", "lz6", "sfhkjgdusd", get_engine_from_name("Löschzug 6")->uuid);
		add_privilege_to_user($user->uuid, ENGINEHYDRANTMANANGER);
		$user = insert_user("LZ 7", "LZ 7", "lz7", "fgdgfsdgfg", get_engine_from_name("Löschzug 7")->uuid);
		add_privilege_to_user($user->uuid, ENGINEHYDRANTMANANGER);
		$user = insert_user("LZ 8", "LZ 8", "lz8", "gisksfghjo", get_engine_from_name("Löschzug 8")->uuid);
		add_privilege_to_user($user->uuid, ENGINEHYDRANTMANANGER);
		$user = insert_user("LZ 9", "LZ 9", "lz9", "fdgisfghkh", get_engine_from_name("Löschzug 9")->uuid);
		add_privilege_to_user($user->uuid, ENGINEHYDRANTMANANGER);
		$user = insert_user("Brandschutzzug", "Brandschutzzug", "bz", "asshzjfghli", get_engine_from_name("Brandschutzzug")->uuid);
		add_privilege_to_user($user->uuid, ENGINEHYDRANTMANANGER);
		$user = insert_user("Verwaltung", "Verwaltung", "verwaltung", "ghjoeduldf", get_engine_from_name("Verwaltung")->uuid);
		add_privilege_to_user($user->uuid, FFADMINISTRATION);
		
		$user = insert_user("Hydrantenadmin", "Hydrantenadmin", "hydrantenadmin", "sf0ihsfaoihf", get_engine_from_name("Keine Zuordnung")->uuid);
		add_privilege_to_user($user->uuid, HYDRANTADMINISTRATOR);
	}
}

?>