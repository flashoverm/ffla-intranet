<?php
require_once REPOSITORIES_PATH . "/db_connect.php";
require_once LIBRARY_PATH . "/mail.php";

create_table_event ();
create_table_staff ();

function insert_event($date, $start, $end, $type_uuid, $type_other, $title, $comment, $engine, $creator, $published, $staff_confirmation) {
	global $db;

	$uuid = getGUID ();
	$hash = hash ( "sha256", $uuid . $date . $start . $end . $type_uuid . $title );

	if($published){
	    
	    $statement = $db->prepare("INSERT INTO event (uuid, date, start_time, end_time, type, type_other, title, comment, engine, creator, published, staff_confirmation, deleted_by, hash)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, ?, NULL, ?)");
	    $statement->bind_param('ssssssssssis', $uuid, $date, $start, $end, $type_uuid, $type_other, $title, $comment, $engine, $creator, $staff_confirmation, $hash);
	    
	} else {
	    
	    $statement = $db->prepare("INSERT INTO event (uuid, date, start_time, end_time, type, type_other, title, comment, engine, creator, published, staff_confirmation, deleted_by, hash)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE, ?, NULL, ?)");
	    $statement->bind_param('ssssssssssis', $uuid, $date, $start, $end, $type_uuid, $type_other, $title, $comment, $engine, $creator, $staff_confirmation, $hash);
	    
	}

	$result = $statement->execute();
	
	if ($result) {
		return $uuid;
	} else {
		//echo "Error: " . "<br>" . $db->error;
		return false;
	}
}

function insert_staff($event_uuid, $position_uuid, $unconfirmed) {
	global $db;
	$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO staff (uuid, position, event, user, unconfirmed) VALUES (?, ?, ?, NULL, ?)");
	$statement->bind_param('sssi', $uuid, $position_uuid, $event_uuid, $unconfirmed);
	
	$result = $statement->execute();

	if ($result) {
	    return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function get_public_events() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM event WHERE date >= (now() - INTERVAL 1 DAY) AND published = TRUE AND deleted_by IS NULL ORDER BY date DESC");
	
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

function get_all_active_events() {
    global $db;
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM event WHERE date >= (now() - INTERVAL 1 DAY) AND deleted_by IS NULL ORDER BY date ASC");
    
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

function get_all_past_events() {
    global $db;
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM event WHERE date < (now() - INTERVAL 1 DAY) AND deleted_by IS NULL ORDER BY date ASC");
    
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
    
function get_events($user_uuid) {
	global $db;
	$data = array ();
	$engine = get_engine_of_user($user_uuid);
	
	$statement = $db->prepare("SELECT * FROM event WHERE (engine = ? OR creator = ?) AND date >= (now() - INTERVAL 1 DAY) AND deleted_by IS NULL ORDER BY date ASC");
	$statement->bind_param('ss', $engine, $user_uuid);
	
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

function get_past_events($user_uuid) {
    global $db;
    $data = array ();
    $engine = get_engine_of_user($user_uuid);
    
    $statement = $db->prepare("SELECT * FROM event WHERE (engine = ? OR creator = ?) AND date < (now() - INTERVAL 1 DAY) AND deleted_by IS NULL ORDER BY date DESC");
    $statement->bind_param('ss', $engine, $user_uuid);
    
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

function get_all_deleted_events(){
    global $db;
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM event WHERE NOT deleted_by IS NULL ORDER BY date ASC");
    
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

function get_staff($event_uuid) {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT staff.uuid, event, user, staff.position, unconfirmed 
		FROM staff, staffposition WHERE event = ? AND staff.position = staffposition.uuid ORDER BY list_index");
	$statement->bind_param('s', $event_uuid);
	
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

function get_occupancy($event_uuid){
    
    $staff = get_staff($event_uuid);
    
    $length = sizeof($staff);
    $occupancy = 0;
    foreach ( $staff as $entry ) {
        if($entry->user != NULL){
            $occupancy ++;
        }
    }
    return $occupancy . "/" . $length;
}

function is_user_already_staff($event_uuid, $user_uuid){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM staff WHERE event = ? AND user = ?");
    $statement->bind_param('ss', $event_uuid, $user_uuid);
    
    $result = $statement->execute();
    
    if ($result && mysqli_num_rows ( $statement->get_result() ) > 0) {
        return true;
    } else {
        return false;
    }
}

function is_event_full($event_uuid){
	global $db;
	
	$statement = $db->prepare("SELECT COUNT(*) AS empty_pos FROM staff WHERE user IS NULL AND event = ?");
	$statement->bind_param('s', $event_uuid);
	
	$result = $statement->execute();
		
	if ($result && $statement->get_result()->fetch_row () [0] == 0) {
		return true;
	} else {
		return false;
	}
}

function is_user_manager_or_creator($event_uuid, $user_uuid){
	$event = get_event($event_uuid);
	
	if($event->creator == $user_uuid){
		return true;
	}
	if(user_has_privilege($user_uuid, ENGINEHYDRANTMANANGER) 
			&& strcmp(get_engine_of_user($user_uuid),$event->engine) == 0){
		return true;
	}
	
	return false;
}

function get_event($event_uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM event WHERE uuid = ?");
	$statement->bind_param('s', $event_uuid);
	
	$result = $statement->execute();

	if ($result) {
		return $statement->get_result()->fetch_object ();
	} else {
		// echo "UUID not found";
	}
}

function get_events_creator($event_uuid){
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user, event WHERE event.creator = user.uuid AND event.uuid = ?");
	$statement->bind_param('s', $event_uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$creator = $result->fetch_object ();
			$result->free ();
			return $creator;
		}
	}
}

function get_events_staff($event_uuid){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user, staff WHERE user.uuid = staff.user AND staff.event = ?");
	$statement->bind_param('s', $event_uuid);
	
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

function get_events_staffposition($staff_uuid){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM staff, staffposition WHERE staff.position = staffposition.uuid AND staff.uuid = ?");
    $statement->bind_param('s', $staff_uuid);
    
    if ($statement->execute()) {
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            $position = $result->fetch_object ();
            $result->free ();
            return $position;
        }
    }
}

function get_staff_user($staff_uuid){
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user, staff WHERE user.uuid = staff.user AND staff.uuid = ?");
	$statement->bind_param('s', $staff_uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$user = $result->fetch_object ();
			$result->free ();
			return $user;
		}
	}
}

function get_personal($event_uuid){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user, staff WHERE event = ? AND staff.user = user.uuid");
	$statement->bind_param('s', $event_uuid);
	
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

function update_event($event_uuid, $date, $start, $end, $type_uuid, $type_other, $title, $comment, $engine, $staff_confirmation){
	global $db;
	
	$statement = $db->prepare("UPDATE event 
		SET date = ?, start_time = ?, end_time = ?, type = ?, type_other = ?, title = ?, comment = ?, engine = ?, staff_confirmation = ?
		WHERE uuid = ?");
	$statement->bind_param('ssssssssis', $date, $start, $end, $type_uuid, $type_other, $title, $comment, $engine, $staff_confirmation, $event_uuid);

	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function update_staff($staff_uuid, $position){
    global $db;
    
    $statement = $db->prepare("UPDATE staff
		SET position = ?
		WHERE uuid = ?");
    $statement->bind_param('ss', $position, $staff_uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        return true;
    } else {
        //echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function confirm_staff_user($staff_uuid){
	global $db;
	
	$statement = $db->prepare("UPDATE staff
		SET unconfirmed = FALSE
		WHERE uuid = ?");
	$statement->bind_param('s', $staff_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function subscribe_staff_user($uuid, $user_uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE staff SET user = ? WHERE user IS NULL AND uuid = ?");
	$statement->bind_param('ss', $user_uuid, $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		
		if($db->affected_rows == 0){
			return 0;
		}
		return 1;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return -1;
	}
}

function add_staff_user($uuid, $user) {
	global $db;
	
	$statement = $db->prepare("UPDATE staff SET user = ?, unconfirmed = FALSE  WHERE uuid = ?");
	$statement->bind_param('ss', $user, $uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function remove_staff_user($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE staff SET user = NULL, unconfirmed = TRUE WHERE uuid= ?");
	$statement->bind_param('s', $uuid);
	
	$result = $statement->execute();

	if ($result) {
	    return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
	    return false;
	}
}

function publish_event($uuid){
    global $db;
    
    $statement = $db->prepare("UPDATE event SET published = TRUE WHERE uuid= ?");
    $statement->bind_param('s', $uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        return true;
    } else {
        // echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function mark_event_as_deleted($uuid, $user_uuid) {
    global $db;
    
    $statement = $db->prepare("UPDATE event SET deleted_by = ? WHERE uuid= ?");
    $statement->bind_param('ss', $user_uuid, $uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        return true;
    } else {
        // echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function delete_staff_entry($staff_uuid) {
	global $db;
	
	$statement = $db->prepare("DELETE FROM staff WHERE uuid= ?");
	$statement->bind_param('s', $staff_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function delete_event($uuid){
    global $db;
    
    $statement = $db->prepare("DELETE FROM staff WHERE event = ?");
    $statement->bind_param('s', $uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        
        $statement = $db->prepare("DELETE FROM event WHERE uuid = ?");
        $statement->bind_param('s', $uuid);
        
        $result = $statement->execute();
        
        if ($result) {
            return true;
        }
    }
    // echo "Error: " . $query . "<br>" . $db->error;
    return false;
}

function create_table_event() {
	global $db;
		
	$statement = $db->prepare("CREATE TABLE event (
                          uuid CHARACTER(36) NOT NULL,
						  date DATE NOT NULL,
                          start_time TIME NOT NULL,
                          end_time TIME,
                          type CHARACTER(36) NOT NULL,
                          type_other VARCHAR(96),
						  title VARCHAR(96),
						  comment VARCHAR(255),
                          engine CHARACTER(36) NOT NULL,
						  creator CHARACTER(36) NOT NULL,
                          published BOOLEAN NOT NULL,
						  staff_confirmation BOOLEAN NOT NULL,
						  deleted_by CHAR(36),
						  hash VARCHAR(64) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (creator) REFERENCES user(uuid),
						  FOREIGN KEY (type) REFERENCES eventtype(uuid),
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

function create_table_staff() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE staff (
						  uuid CHARACTER(36) NOT NULL,
                          position CHARACTER(36) NOT NULL,
                          event CHARACTER(36) NOT NULL,
						  user CHARACTER(36),
						  unconfirmed BOOLEAN NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid),
						  FOREIGN KEY (event) REFERENCES event(uuid),
                          FOREIGN KEY (position) REFERENCES staffposition(uuid)
                          )");
	
	$result = $statement->execute();

	if ($result) {
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}

?>