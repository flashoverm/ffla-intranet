<?php
require_once "db_connect.php";

create_table_files();


function insert_file($description, $date, $filename){
    global $db;
    
    $uuid = getGUID ();
    
    $statement = $db->prepare("INSERT INTO file (uuid, description, date, filename) VALUES (?, ?, ?, ?)");
    $statement->bind_param('ssss', $uuid, $description, $date, $filename);
    
    $result = $statement->execute();
    
    if ($result) {
        // echo "Record ".$fid." updated successfully";
    	return $uuid;
    } else {
        // echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function get_files(){
    global $db;
    
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM file ORDER BY description");
    if($statement){
    	if ($statement->execute()) {
    		$result = $statement->get_result();
    		
    		if (mysqli_num_rows ( $result )) {
    		    while ( $date = $result->fetch_object () ) {
    		        $data [] = $date;
    		    }
    		    $result->free ();
    		}
    	}
    }
    return $data;
}

function get_file($uuid){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM file WHERE uuid = ?");
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


function delete_file($uuid){
    global $db;
    
    $statement = $db->prepare("DELETE FROM file WHERE uuid= ?");
    $statement->bind_param('s', $uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        // echo "Record ".$fid." updated successfully";
        return true;
    } else {
        // echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function create_table_files() {
    global $db;
    
    $statement = $db->prepare("CREATE TABLE file (
						  uuid CHARACTER(36) NOT NULL,
                          description VARCHAR(255) NOT NULL,
                          date DATETIME  NOT NULL,
						  filename VARCHAR(255) NOT NULL,
                          PRIMARY KEY  (uuid)
                          )");
    
    $result = $statement->execute();
    
    if ($result) {
        // echo "Table created<br>";
        return true;
    } else {
        //echo "Error: " . $db->error . "<br><br>";
        return false;
    }
}

?>