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
			$firstname = trim($columns[0]);
			$lastname = trim($columns[1]);
			$engine = $engineDAO->getEngine($_POST['engine']);
			$password = $userController->randomPassword();
			
			$user = new User();
			$user->setUserData($firstname, $lastname, $email, $engine, "", "");
			$user->setPassword($password);
			
			if(sizeof($columns) == 3){
				
				try{
					$user = $userController->createNewUser($user);
					$user = $userDAO->save($user);
					
					if($user){
												
						$mail = mail_add_user($email, $password);
						
						$imported++;
						
						$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserImported, $user->getUuid()));
						
					}
				
				} catch(Exception $e) {
					switch ($e->getCode()){
						case 101:
							$errorString .=  "E-Mail-Adresse bereits mit anderem Namen/Zug in Verwendung:\t" . col_to_string($columns). "<br>";
							break;
						case 102:
							$errorString .=  "Diese E-Mail-Adresse ist bereits vergeben:\t" . col_to_string($columns). "<br>";
							break;
						default:
							$errorString .=  "Ein unbekannter Fehler ist aufgetreten:\t" . col_to_string($columns). "<br>";
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
