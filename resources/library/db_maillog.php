<?php
require_once LIBRARY_PATH . "/db_connect.php";

create_table_maillog();

function insert_maillog($recipient, $subject, $state, $body, $error = NULL) {
	global $db;
	$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO maillog (uuid, timestamp, recipient, subject, state, body, error) 
		VALUES (?, now(), ?, ?, ?, ?, ?)");
	$statement->bind_param('sssiss', $uuid, $recipient, $subject, $state, $body, $error);
	
	$result = $statement->execute();
	
	if ($result) {
		// echo "New record created successfully<br>";
		return $uuid;
	} else {
		//echo "Error: " . $statement . "<br>" . $db->error . "<br><br>";
		return false;
	}
}

function get_maillogs($page, $resultSize = 20) {
	global $db;
	$data = array ();
	
	$fromRowNumber = (($page-1)*$resultSize)+1;
	$toRowNumber = $fromRowNumber+$resultSize;
	
	$db->query("SET @row_number = 0;");
		
	$statement = $db->prepare("
		SELECT  * 
		FROM ( SELECT *, (@row_number:=@row_number + 1) AS RowNum FROM maillog ORDER BY timestamp DESC) AS Data
		WHERE   RowNum >= ? AND RowNum < ? 
		ORDER BY RowNum");
	$statement->bind_param('ii', $fromRowNumber, $toRowNumber);
	
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

function get_maillogs_count() {
	global $db;
	
	$statement = $db->prepare("SELECT count(*) AS count FROM maillog");
	
	if ($statement->execute()) {
		$result = $statement->get_result()->fetch_object()->count;
		return $result;
	} else {
		//echo "Error: " . $statement . "<br>" . $db->error . "<br><br>";
		return false;
	}
}
	

function clear_maillog() {
	global $db;
	
	$statement = $db->prepare("DELETE FROM maillog");
	
	$result = $statement->execute();
	
	if ($result) {
		return true;
	} else {
		//echo "Error: " . $statement . "<br>" . $db->error . "<br><br>";
		return false;
	}
}

function create_table_maillog() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE maillog (
						  uuid CHARACTER(36) NOT NULL,
                          timestamp datetime NOT NULL,
						  recipient VARCHAR(255) NOT NULL,
						  subject VARCHAR(255) NOT NULL,
						  state TINYINT NOT NULL,
						  body TEXT,
						  error VARCHAR(255),
                          PRIMARY KEY (uuid)
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