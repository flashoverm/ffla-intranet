<?php
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_connect.php";

create_table_user ();

function insert_user($username, $password, $engine_uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE username = ? AND engine = ?");
	$statement->bind_param('ss', $username, $engine_uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			return $data;
		}
	}
	
	$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO user (uuid, username, password, engine, rights)
		VALUES (?, ?, ?, ?, NULL)");
	$statement->bind_param('ssss', $uuid, $username, $password, $engine_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		
		$statement = $db->prepare("SELECT * FROM user WHERE uuid = ?");
		$statement->bind_param('s', $uuid);
		$statement->execute();
		$data = $statement->get_result()->fetch_object ();
		return $data;
		
	} else {
		// echo "Error: " . $db->error;
		return false;
	}
}

function get_users(){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user ORDER BY username");
	
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

function get_user_by_name($username){
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE username = ?");
	$statement->bind_param('s', $username);
	
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

function get_rights($uuid){
	global $db;
	$statement = $db->prepare("SELECT rights FROM user WHERE uuid = ?");
	$statement->bind_param('s', $uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object();
			$result->free ();
			if($data){
				return json_decode($data->rights);
			}
		}
	}
	return false;
}

function is_fileadmin($uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT isfileadmin FROM user WHERE isfileadmin = TRUE AND uuid = ?");
	$statement->bind_param('s', $uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_row ();
			$result->free ();
			return $data [0];
		}
	}
	return FALSE;
}


function hasRight($uuid, $right){
	$rights = get_rights($uuid);	
	if($rights){
		if(in_array($right, $rights)){			
			return true;
		}
	}
	return false;
}

function userHasRight($right){
	if(isset ($_SESSION ['intranet_userid'])){
		return hasRight($_SESSION ['intranet_userid'], $right);
	}
	return false;
}

function check_password($username, $password) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE username = ?");
	$statement->bind_param('s', $username);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			if ($password == $data->password ) {
				return $data->uuid;
			}
		}
	}
	return false;
}

function addRight($uuid, $right){
	global $db;
	
	$rights = get_rights($uuid);
	if($rights){
		if(!in_array ($right, $rights)){
			$rights[] = $right;
		}
	} else {
		$rights = array();
		$rights[] = $right;
	}
	
	$rightsJson = json_encode($rights);
		
	$statement = $db->prepare("UPDATE user SET rights = ? WHERE uuid = ?");
	$statement->bind_param('ss', $rightsJson, $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function removeRight($uuid, $right){
	global $db;
	
	$rights = get_rights($uuid);
	if($rights && in_array ($right, $rights)){
		$idx = array_search($right, $rights);
		unset($rights[$idx]);
	} else {
		$rights = array();
	}
		
	$rightsJson = json_encode($rights);
	
	$statement = $db->prepare("UPDATE user SET rights = ? WHERE uuid = ?");
	$statement->bind_param('ss', $rightsJson, $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function create_table_user() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE user (
                          uuid CHARACTER(36) NOT NULL,
                          username VARCHAR(96) NOT NULL,
                          password VARCHAR(255),
						  engine CHARACTER(36) NOT NULL,
						  rights VARCHAR(255),
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
	
	$result = $statement->execute();
	
	if ($result) {
				
		insert_user("fflandshut", "ffla112", get_engine_from_name("Keine Zuordnung")->uuid);
		$user = insert_user("fffiles", "ffla112", get_engine_from_name("Keine Zuordnung")->uuid);
		addRight($user->uuid, FILEADMIN);
		
		insert_user("lz12", "daigfhrfd", get_engine_from_name("Löschzug 1/2")->uuid);
		insert_user("lz3", "ddfgohwsfh", get_engine_from_name("Löschzug 3")->uuid);
		insert_user("lz4", "sgfjgsksfn", get_engine_from_name("Löschzug 4")->uuid);
		insert_user("lz5", "xvhkgdjdaf", get_engine_from_name("Löschzug 5")->uuid);
		insert_user("lz6", "sfhkjgdusd", get_engine_from_name("Löschzug 6")->uuid);
		insert_user("lz7", "fgdgfsdgfg", get_engine_from_name("Löschzug 7")->uuid);
		insert_user("lz8", "gisksfghjo", get_engine_from_name("Löschzug 8")->uuid);
		insert_user("lz9", "fdgisfghkh", get_engine_from_name("Löschzug 9")->uuid);
		insert_user("bz", "asshzjfghli", get_engine_from_name("Brandschutzzug")->uuid);
		$user = insert_user("verwaltung", "ghjoeduldf", get_engine_from_name("Verwaltung")->uuid);
		addRight($user->uuid, FFADMINISTRATION);
		
		$user = insert_user("admin", "admin", get_engine_from_name("Löschzug 1/2")->uuid);
		addRight($user->uuid, ENGINEHYDRANTMANANGER);
		
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}

?>