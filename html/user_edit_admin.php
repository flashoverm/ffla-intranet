<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

$privileges = $privilegeDAO->getPrivileges();

// Pass variables (as an array) to template
$variables = array (
	'secured' => true,
	'privilege' => Privilege::PORTALADMIN,
	'engines' => $engineDAO->getEngines(),
	'privileges' => $privileges,
);

if( isset($_GET['uuid']) ){
	// edit by admin
	//		uuid from get param
	//		no password edit
	
	$variables['title'] = "Benutzer bearbeiten";
	
	$user = $userDAO->getUserByUUID($_GET['uuid']);
	if($user){
		$variables['user'] = $user;
	} else {
		$variables ['alertMessage'] = "Benutzer wurde nicht gefunden";
	}

} else {
	// create new user
	//		all data are entered in form (including privileges and password
	
	$variables['title'] = "Benutzer anlegen";
}



if (isset ( $_POST ['useremail'] ) ) {
	
	$firstname = trim($_POST ['firstname']);
	$lastname = trim($_POST ['lastname']);
	$email = strtolower(trim($_POST ['useremail']));
	$engineUuid = trim($_POST ['engine']);	
	$engine = $engineDAO->getEngine($engineUuid);
	
	$employerAddress = null;
	if(isset($_POST['employerAddress'])){
		$employerAddress = trim($_POST['employerAddress']);
	}
	
	$employerMail = null;
	if(isset($_POST['employerMail'])){
		$employerMail = trim($_POST['employerMail']);
	}
	
	$uuid = null;
	if(isset($_GET['uuid'])){
		$uuid = trim($_GET['uuid']);
	}
	
	$newPrivileges = array();
	foreach($privileges as $privilege){
		
		$inputName = "priv_" . $privilege->getUuid();
		if(isset ( $_POST [ $inputName ] )){
			$newPrivileges [] = $privilege;
		}
	}
	
	//check if password equals (if set)
	$exit = false;
	if(isset($_POST ['userpassword'])){
		$password = trim($_POST ['userpassword']);
		$password2 = trim($_POST ['userpassword2']);
		
		if ($password != $password2) {
			$variables ['alertMessage'] = 'Die Passwörter müssen übereinstimmen';
			$exit = true;
		}
	}
	
	if (! $exit) {
				
		if($uuid == null){
			
			//New user is requested
			
			$user = new User();
			$user->setUserData($firstname, $lastname, $email, $engine, $employerAddress, $employerMail);
			$user->setPassword($password);
			
			try{
				$user = $userController->createNewUser($user);
				$user->resetPrivileges($newPrivileges);
				$userDAO->save($user);
				
				if($user){
					//Password and selected privileges added to existing non-login-user or new user created
					
					$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserCreated, $user->getUuid()));
					$variables ['successMessage'] = "Der Benutzer wurde angelegt. Die Zugangsdaten wurden per E-Mail übermittelt.";
					
					unset($_POST);
					unset($variables['user']);
					
					$mail = mail_add_user($email, $password);
					if(!$mail){
						$variables ['alertMessage'] = "E-Mail konnte nicht versendet werden";
					}
					
					//header ( "Location: " . $config["urls"]["intranet_home"] . "/login" ); // redirects
				}
				
			} catch(Exception $e) {
				switch ($e->getCode()){
					case 101:
						$variables ['alertMessage'] = "E-Mail-Adresse bereits mit anderem Namen/Zug in Verwendung!";
						break;
					case 102:
						$variables ['alertMessage'] = "Diese E-Mail-Adresse ist bereits vergeben";
						break;
					default:
						$variables ['alertMessage'] = "Ein unbekannter Fehler ist aufgetreten";
				}
			}
			
		} else {
			
			//Edit user with given uuid
			
			$user = $variables['user'];
			
			if($user->getEmail() != $email && $userController->isEmailInUse($email)){
				//new email address is entered but the new one is already in use
				$variables ['alertMessage'] = "Die eingegeben E-Mail-Adresse '" . $email . "' ist bereits vergeben";
			} else {
				
				$user->setUserData($firstname, $lastname, $email, $engine, $employerAddress, $employerMail);
				$user->resetPrivileges($newPrivileges);
				$user = $userDAO->save($user);
				
				if($user){
					$variables['user'] = $user;
					$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserUpdated, $user->getUuid()));
					$variables ['successMessage'] = "Der Benutzer wurde aktualisiert";
				} else {
					$variables ['alertMessage'] = "Der Benutzer konnte nicht aktualisiert werden";
				}
			}
		}
	}
}

renderLayoutWithContentFile ($config["apps"]["landing"], "userEdit/userEditAdmin_template.php", $variables );
?>