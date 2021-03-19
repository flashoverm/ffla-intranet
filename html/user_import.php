<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

define("DELIMITER", ";");

$engines = $engineDAO->getEngines();

// Pass variables (as an array) to template
$variables = array (
		'title' => "Daten-Import",
		'secured' => true,
		'privilege' => Privilege::EVENTADMIN,
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
				if($userController->isEmailInUse($email)){
					$errorString .=  "Benutzer bereits vorhanden:\t" . col_to_string($columns). "<br>";
				} else {
					$firstname = trim($columns[0]);
					$lastname = trim($columns[1]);
					$user = new User();
					$user->setUserData($firstname, $lastname, $email, $engineDAO->getEngine($_POST['engine']), null, null);
					if($userController->createNewUser($user)){
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
