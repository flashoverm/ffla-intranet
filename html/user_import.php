<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_user.php";

define("DELIMITER", ";");

$engines = get_engines();

// Pass variables (as an array) to template
$variables = array (
		'title' => "Daten-Import",
		'secured' => true,
		'privilege' => EVENTADMIN,
		'engines' => $engines,
);

if(isset($_POST['engine'])){
	
	$handle = fopen($_FILES['import']['tmp_name'],"r");
	if ($handle) {
		$errorString = false;
		$imported = 0;
		$lines = 0;
		while (($line = fgets($handle)) !== false) {
			$lines++;
			$columns = explode(DELIMITER, $line);
			$email = trim(strToLower($columns[2]));
			
			if(sizeof($columns) == 3){
				if(email_in_use($email)){
					$errorString .=  "Benutzer bereits vorhanden:\t" . col_to_string($columns). "<br>";
				} else {
					$firstname = trim($columns[0]);
					$lastname = trim($columns[1]);
					if(insert_user($firstname, $lastname, $email, $_POST['engine'], null, null)){
						$imported++;
					}
				}
			} else {
				$errorString .=  "Falsches Datenformat:\t\t\t" . col_to_string($columns). "<br>";
			}
		}
		
		fclose($handle);
	} else {
		$errorString .= "Datei " . $file . " kann nicht gelesen werden ";
	}

	if($imported){
		$variables ['successMessage'] = $imported . " von " . $lines ." Benutzer importiert";
	} else {
		$errorString .= "Keine Benutzer importiert";
	}
	
	if($errorString){
		$variables['alertMessage'] = $errorString;
	}
}

renderLayoutWithContentFile ($config["apps"]["landing"], "userImport_template.php", $variables );

function col_to_string($columns){
	return $columns[0] . " " . $columns[1] . " - " . $columns[2];
}
