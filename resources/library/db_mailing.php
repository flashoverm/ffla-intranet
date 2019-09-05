<?php
require_once LIBRARY_PATH . "/db_connect.php";

create_table_mailing();

function insert_membership($email, $membership){
    global $db;
    
    if(get_memberships($email)){      
        $memberof = get_memberships($email);
        if($memberof){
            if(!in_array ($email, $memberof)){
                $memberof[] = $membership;
            }
        } else {
            $memberof = array();
            $memberof[] = $membership;
        }
        
        $memberofJson = json_encode($memberof);
        
        $statement = $db->prepare("UPDATE mailing SET memberof = ? WHERE email = ?");
        $statement->bind_param('ss', $memberofJson, $email);
        
        $result = $statement->execute();
        
        if ($result) {
            return true;
        } else {
            //echo "Error: " . $query . "<br>" . $db->error;
            return false;
        }
    } else {
        $memberof = array();
        $memberof[] = $membership;
        
        $memberofJson = json_encode($memberof);
        
        $statement = $db->prepare("INSERT INTO mailing (email, memberof)
		VALUES (?, ?)");
        $statement->bind_param('ss', $email, $memberofJson);
        
        $result = $statement->execute();
        
        if ($result) {
            return true;
        } else {
            // echo "Error: " . $db->error;
            return false;
        }
    }
}

function get_member($membership){
    global $db;
    
    $data = array ();
    
    $searchString = "%" . $membership . "%";
    
    $statement = $db->prepare('SELECT * FROM mailing WHERE memberof LIKE ?');
    $statement->bind_param('s', $searchString);
    
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

function get_memberships($email){
    global $db;
    $statement = $db->prepare("SELECT memberof FROM mailing WHERE email = ?");
    $statement->bind_param('s', $email);
    
    if ($statement->execute()) {
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            $data = $result->fetch_object();
            $result->free ();
            if($data){
                return json_decode($data->memberof);
            }
        }
    }
    return false;
}

function is_member_of($email, $membership) {
    $memberof = get_memberships($email);
    if($memberof){
        if(in_array($membership, $memberof)){
            return true;
        }
    }
    return false;
}

function remove_membership($email, $membership){
    global $db;
    
    $memberof = get_memberships($email);
    if($memberof && in_array ($email, $memberof)){
        $idx = array_search($email, $memberof);
        unset($memberof[$idx]);
    } else {
        $memberof = array();
    }
    
    $memberofJson = json_encode($memberof);
    
    $statement = $db->prepare("UPDATE mailing SET memberof = ? WHERE email = ?");
    $statement->bind_param('ss', $memberofJson, $email);
    
    $result = $statement->execute();
    
    if ($result) {
        return true;
    } else {
        //echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function create_table_mailing() {
    global $db;
    
    $statement = $db->prepare("CREATE TABLE mailing (
                          email VARCHAR(255) NOT NULL,
						  memberof VARCHAR(255),
                          PRIMARY KEY  (email)
                          )");
    
    $result = $statement->execute();
    
    if ($result) {
        insert_membership("markus@thral.de", INSPECTIONREPORT);
        return true;
    } else {
        // echo "Error: " . $db->error . "<br><br>";
        return false;
    }
}
