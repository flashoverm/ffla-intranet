<?php 
require_once "../db_connect.php";
require_once "../db_privilege.php";
require_once "../db_user.php";

$users = get_all_user();

$allok = true;

foreach ( $users as $user ) {
	
	$rights = get_rights($user->uuid);
	
	foreach ( $rights as $right ) {
		
		
		$ok = add_privilege_to_user($user->uuid, $right);
		echo $ok . ":" . $user->firstname . " " . $user->lastname . ": " . $right . "\n";
		$allok = $allok && $ok;
		
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
	if(isset ($_SESSION ['guardian_userid'])){
		return hasRight($_SESSION ['guardian_userid'], $right);
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