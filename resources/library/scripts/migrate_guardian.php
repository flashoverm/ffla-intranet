<?php 
require_once "../db_connect.php";
require_once "../db_privilege.php";
require_once "../db_user.php";

$dump_db = "guardian_dump";
$db_dump = new mysqli ( $config ['db'] ['host'], $config ['db'] ['username'], $config ['db'] ['password'], $dump_db );
$db_dump->set_charset('utf8');

create_table_user_privilege();
create_table_privilege();

$result = $db->query("ALTER TABLE user" 
	. " CHANGE username email VARCHAR(128) NOT NULL,"
	. " ADD COLUMN firstname VARCHAR(64) NOT NULL AFTER password,"
	. " ADD COLUMN lastname VARCHAR(64) NOT NULL AFTER firstname,"
	. " ADD COLUMN locked BOOLEAN NOT NULL AFTER engine"
);

$db->query("UPDATE user SET firstname = email");
$db->query("UPDATE user SET lastname = email");


$result = $db_dump->query("SELECT * FROM user");
while ( $user = $result->fetch_object () ) {
	$resultInsert = $db->query("INSERT INTO user VALUES ("
			. "'" . $user->uuid . "', "
			. "'" . $user->email . "', "
			. "'" . $user->password . "', "
			. "'" . $user->firstname . "', "
			. "'" . $user->lastname . "', "
			. "'" . $user->engine . "', "
			. "FALSE, "
			. "'" . $user->rights . "'"
			.")");
	if(!$resultInsert){
		echo "Error migrating user " . $user->email . ": " . $db->error . "\n";
	}
	if($user->available){
		add_privilege_to_user_by_name($user->uuid, EVENTPARTICIPENT);
 	}
	
 }

migrate_rights();

migrateTables();


$admin = get_user_by_email("admin");
add_privilege_to_user_by_name($admin->uuid, PORTALADMIN);


function migrateTables(){
	
	$tables = array("eventtype", "event", "report", "staffposition", "staff", "report_unit", "report_staff", "stafftemplate");
	
	foreach($tables as $table){
		migrateTable($table);
	}
}

function migrateTable($tablename){
	global $db, $config, $dump_db, $db_dump;

	$result = $db->query("DROP TABLE " . $tablename);

	$result = $db_dump->query("SHOW CREATE TABLE " . $tablename);
	$createString = $result->fetch_assoc()["Create Table"];
	
	$statement = $db->prepare($createString);
	$result = $statement->execute();

	
	if ($result) {
		// echo "Record ".$fid." updated successfully";
		$statement = $db->prepare("INSERT " . $config ['db'] ['dbname'] . "." . $tablename . " SELECT * FROM " . $dump_db . "." . $tablename);
		$result = $statement->execute();
		
		if($result) {
			echo "Copied table " . $tablename . " to " . $config ['db'] ['dbname'] . "\n";
			return true;
		} else {
			echo "Error: inserting content in " . $tablename . ": ". $db->error . "\n";
			return false;
		}
	} else {
		echo "Error: copying table " . $tablename . ": ". $db->error . "\n";
		return false;
	}
}

function migrate_rights(){
	global $db;
	$users = get_users();
	
	$allok = true;
	
	foreach ( $users as $user ) {
		
		$rights = get_rights($user->uuid);
		
		if($rights){
			foreach ( $rights as $right ) {
				
				
				$ok = add_privilege_to_user_by_name($user->uuid, $right);
				echo $ok . ":" . $user->firstname . " " . $user->lastname . ": " . $right . "\n";
				$allok = $allok && $ok;
				
			}
		}

	}
	
	if($allok){
		$statement = $db->prepare("ALTER TABLE user DROP rights");
		$result = $statement->execute();
		
		if ($result) {
			echo "Altered table user (removed column rights)\n";
		} else {
			echo "Cloud not alter table user (remove column rights)\n";
		}
	} else {
		echo "Not all rights migrated - Keep column user.rights\n";
	}
}


/**
 * Old right methods
 */

function get_rights($user_uuid){
	global $db;
	$statement = $db->prepare("SELECT rights FROM user WHERE uuid = ?");
	$statement->bind_param('s', $user_uuid);
	
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