<?php

require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/password.php";
require_once LIBRARY_PATH . "/mail.php";
require_once LIBRARY_PATH . "/db_connect.php";
require_once LIBRARY_PATH . "/db_user_guardian.php";
create_table_user ();
require_once LIBRARY_PATH . "/db_privilege.php";

insert_admin("Admin", "Admin", "admin@guardian.de", "admin", "2BAA144B-F946-1524-E60E-7DD485FE1881");

function insert_user($firstname, $lastname, $email, $engine_uuid) {
	global $db;

	$uuid = getGUID ();
	$mail = strtolower($email);
	
	$statement = $db->prepare("INSERT INTO user (uuid, firstname, lastname, email, password, engine, loginenabled, available) 
		VALUES (?, ?, ?, ?, NULL, ?, FALSE, TRUE)");
	$statement->bind_param('sssss', $uuid, $firstname, $lastname, $mail, $engine_uuid);
	
	$result = $statement->execute();

	if ($result) {
		
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

function get_all_user() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user ORDER BY lastname");
	
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

function get_all_available_user() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user WHERE available = TRUE ORDER BY lastname");
	
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

function get_user_of_engine($engine_uuid){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user WHERE engine = ? AND available = TRUE ORDER BY lastname");
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

function get_user($uuid) {
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

function login_enabled($email) {
	global $db;
	$mail = strtolower($email);
	
	$statement = $db->prepare("SELECT loginenabled FROM user WHERE email = ?");
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

function check_password($email, $password) {
	global $db;
	$mail = strtolower($email);
	
	$statement = $db->prepare("SELECT * FROM user WHERE email = ?");
	$statement->bind_param('s', $mail);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			if (password_verify ( $password, $data->password )) {
				return $data->uuid;
			}
		}
	}
	return false;
}


function deactivate_user($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET loginenabled = FALSE WHERE uuid= ?");
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

function reactivate_user($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET loginenabled = TRUE WHERE uuid= ?");
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

function reset_password($uuid) {
	$password = random_password ();
	$pwhash = password_hash ( $password, PASSWORD_DEFAULT );

	global $db;
	
	$statement = $db->prepare("UPDATE user SET password = ? WHERE uuid = ?");
	$statement->bind_param('ss', $pwhash, $uuid);
	
	$result = $statement->execute();

	if ($result) {
		// echo "Record ".$uuid." updated successfully";
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
			if (password_verify ( $old_password, $data->password )) {
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
						  firstname VARCHAR(64) NOT NULL,
                          lastname VARCHAR(64) NOT NULL,
                          email VARCHAR(96) NOT NULL UNIQUE,
                          password VARCHAR(255),
						  engine CHARACTER(36) NOT NULL,
						  rights VARCHAR(255),
						  loginenabled BOOLEAN NOT NULL,
                          available BOOLEAN NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
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