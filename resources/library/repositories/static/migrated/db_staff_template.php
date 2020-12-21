<?php
require_once "db_connect.php";
/*
create_table_staff_template();

function insert_template($eventtype, $staffposition){
	global $db;
	
	$uuid = getGUID();
	
	
	$statement = $db->prepare("INSERT INTO stafftemplate (uuid, eventtype, staffposition)
		VALUES (?, ?, ?)");
	
	$statement->bind_param('sss', $uuid, $eventtype, $staffposition);
	
	$result = $statement->execute();
	
	if ($result) {
		// echo "New event record created successfully";
		return $uuid;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
	
}

function insert_template_description($eventtype, $staffposition){
    global $db;
    
    $uuid = getGUID();
    
    
    $statement = $db->prepare("INSERT INTO stafftemplate (uuid, eventtype, staffposition)
		VALUES (?,
            (SELECT uuid FROM eventtype WHERE type = ?),
            (SELECT uuid FROM staffposition WHERE position = ?)
        )");
    
    $statement->bind_param('sss', $uuid, $eventtype, $staffposition);
    
    $result = $statement->execute();
    
    if ($result) {
        // echo "New event record created successfully";
        return $uuid;
    } else {
        //echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
    
} 

function get_staff_template($eventtype_uuid){
    global $db;
    $data = array ();
    
    $statement = $db->prepare("SELECT stafftemplate.uuid AS template ,staffposition.uuid, staffposition.position 
            FROM stafftemplate, staffposition 
            WHERE staffposition.uuid = stafftemplate.staffposition AND stafftemplate.eventtype = ?
            ORDER BY staffposition.list_index");
    $statement->bind_param('s', $eventtype_uuid);
        
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

function update_template_entry($template_uuid, $position_uuid){
	global $db;
	
	$statement = $db->prepare("UPDATE stafftemplate
		SET staffposition = ?
		WHERE uuid = ?");
	$statement->bind_param('ss', $position_uuid, $template_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		// echo "New event record created successfully";
		return true;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function delete_template_entry($entry_uuid){
	global $db;
	
	$statement = $db->prepare("DELETE FROM stafftemplate WHERE uuid= ?");
	$statement->bind_param('s', $entry_uuid);
	
	$result = $statement->execute();
	
	if ($result) {
		// echo "Record ".$uuid." updated successfully";
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function create_table_staff_template(){
    global $db;
    
    $statement = $db->prepare("CREATE TABLE stafftemplate (
                          uuid CHARACTER(36) NOT NULL,
                          eventtype CHARACTER(36) NOT NULL,
                          staffposition CHARACTER(36) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (eventtype) REFERENCES eventtype(uuid),
						  FOREIGN KEY (staffposition) REFERENCES staffposition(uuid)
                          )");
    
    $result = $statement->execute();
    
    if ($result) {
        // echo "Table created<br>";
    	insert_template_description("Theaterwache","Dienstgrad (HFM)");
    	insert_template_description("Theaterwache","Wachmann");
    	insert_template_description("Theaterwache","Wachmann");
        
    	insert_template_description("Theaterwache Schüler","Dienstgrad (HFM)");
    	insert_template_description("Theaterwache Schüler","Wachmann");
        insert_template_description("Theaterwache Schüler","Wachmann");
        
        insert_template_description("Theaterwache Prantlgarten","Dienstgrad (HFM)");
        insert_template_description("Theaterwache Prantlgarten","Wachmann");
        insert_template_description("Theaterwache Prantlgarten","Wachmann");

        insert_template_description("Dultwache","Dienstgrad (LM)");
        insert_template_description("Dultwache","Maschinist");
        insert_template_description("Dultwache","Atemschutzträger");
        insert_template_description("Dultwache","Atemschutzträger");
        
        return true;
    } else {
        // echo "Error: " . $db->error . "<br><br>";
        return false;
    }
}
*/