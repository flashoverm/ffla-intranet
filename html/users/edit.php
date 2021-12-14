<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["users"],
		'template' => "userEdit/userEdit_template.php",
		'secured' => true,
		'engines' => $engineDAO->getEngines(),
		'privileges' => $privilegeDAO->getPrivileges(),
);

if( $userController->getCurrentUser() != null ){
	// edit by user itself
	//		uuid from current user
	//		no password edit
	//		no privilege edit
	
	$variables['title'] = "Benutzer bearbeiten";
	$variables['userSelfEdit'] = true;
	$variables['privilege'] = Privilege::EDITUSER;
	$variables['user'] = $userController->getCurrentUser();

} else {
	// register
	//		password given
	//		default privileges assigned
	
	$variables['title'] = "Benutzer erstellen";

	if( $config ["settings"] ["selfregistration"]){
		$variables['secured'] = false;
	}
}
$variables = checkSitePermissions($variables);

if (isset ( $_POST ['useremail'] ) ) {

	$firstname = trim($_POST ['firstname']);
	$lastname = trim($_POST ['lastname']);
	$email = strtolower(trim($_POST ['useremail']));
	
	if(isset($variables['userSelfEdit'])){
		$engine = $variables['user']->getMainEngine();
	} else {
		$engineUuid = trim($_POST ['engine']);
		$engine = $engineDAO->getEngine($engineUuid);
	}
	
	$employerAddress = null;
	if(isset($_POST['employerAddress'])){
		$employerAddress = trim($_POST['employerAddress']);
	}
	
	$employerMail = null;
	if(isset($_POST['employerMail'])){
		$employerMail = trim($_POST['employerMail']);
	}
	
	$exit = false;
	
	//check if password equals (if set)
	$password = NULL;
	if(isset($_POST ['userpassword'])){
		$password = trim($_POST ['userpassword']);
		$password2 = trim($_POST ['userpassword2']);
		
		if ($password != $password2) {
			$variables ['alertMessage'] = 'Die Passwörter müssen übereinstimmen';
			$exit = true;
		}
	}
	
	
	if (! $exit) {

		if( ! isset($variables['userSelfEdit']) || ! $variables['userSelfEdit']){
			
			//New user is requested
			
			$user = new User();
			$user->setUserData($firstname, $lastname, $email, $engine, $employerAddress, $employerMail);
			$user->setPassword($password);
			
			try{
				
				$user = $userController->createNewUser($user);

				if($user){
					//Password and default privileges added to existing non-login-user or new user created
					
					$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserCreated, $user->getUuid()));
					$variables ['successMessage'] = "Der Benutzer wurde angelegt. Die Zugangsdaten wurden per E-Mail übermittelt. <a href='" . $config["urls"]["intranet_home"] . "/login'>Weiter zum Login</a>";
					
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
			
			//Edit own user
			
			$user = $variables['user'];
			
			if($user->getEmail() != $email && $userController->isEmailInUse($email)){
				//new email address is entered but the new one is already in use
				$variables ['alertMessage'] = "Die eingegeben E-Mail-Adresse '" . $email . "' ist bereits vergeben";
			} else {
				
				$user->setUserData($firstname, $lastname, $email, $engine, $employerAddress, $employerMail);
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

renderLayoutWithContentFile ($variables );
?>