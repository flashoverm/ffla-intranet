<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

$privileges = $privilegeDAO->getPrivileges();

// Pass variables (as an array) to template
$variables = array (
	'secured' => true,
	'engines' => $engineDAO->getEngines(),
	'privileges' => $privileges,
);

if( isset($_GET['self']) ){
	// edit by user itself
	$variables['title'] = "Benutzer bearbeiten";
	
	$variables['showRights'] = false;
	$variables['user'] = $userController->getCurrentUser();
	$variables['privilege'] = Privilege::EDITUSER;
	
	
} else if( isset($_GET['uuid']) ){
	// edit by admin
	$variables['title'] = "Benutzer bearbeiten";
	$variables['privilege'] = Privilege::PORTALADMIN;
	
	$variables['showRights'] = true;
	$user = $userDAO->getUserByUUID($_GET['uuid']);
	if($user){
		$variables['user'] = $user;
	} else {
		$variables ['alertMessage'] = "Benutzer wurde nicht gefunden";
	}
	
} else {
	// new user
	$variables['title'] = "Benutzer anlegen";
	
	if($userController->hasCurrentUserPrivilege(Privilege::PORTALADMIN)){
		$variables['showRights'] = true;
	} else {
		$variables['showRights'] = false;
	}
	if( $config ["settings"] ["selfregistration"]){
		$variables['secured'] = false;
	} else {
		$variables['privilege'] = Privilege::PORTALADMIN;
	}
	
}



if (isset ( $_POST ['useremail'] ) && isset ( $_POST ['engine'] ) && isset ( $_POST ['firstname'] ) && isset ( $_POST ['lastname'] )) {

	$firstname = trim($_POST ['firstname']);
	$lastname = trim($_POST ['lastname']);
	$email = strtolower(trim($_POST ['useremail']));
	$engine = trim($_POST ['engine']);	
	
	$employerAddress = null;
	if(isset($_POST['employerAddress'])){
		$employerAddress = trim($_POST['employerAddress']);
	}
	
	$employerMail = null;
	if(isset($_POST['employerMail'])){
		$employerMail = trim($_POST['employerMail']);
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
		/*
		 * logic for adding login (password) to existing user
		 * if mail is in use and entered data matches with user, create password for user
		 * else: if user is updated (uuid or self is set as parameter) skip
		 * 		 else: print error
		 */
		if ($userController->isEmailInUse( $email )) {
			$user = $userDAO->getUserByData($firstname, $lastname, $email, $engine);
			if($user){
				if($user->password == NULL){
					if(reset_password($user->uuid)){
						$variables ['successMessage'] = "Das Passwort wurde gesetzt und gesendet";
						$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserResetPassword, $user->getUuid()));
					} else {
						$variables ['alertMessage'] = "Ein unbekannter Fehler ist aufgetreten";
					}
					$exit = true;
				} else {
					if( ! isset($_GET['uuid']) && ! isset($_GET['self']) ){
						$variables ['alertMessage'] = "Diese E-Mail-Adresse ist bereits vergeben";
						$exit = true;
					}
				}
			} else {
				if(! isset($_GET['uuid'])  && ! isset($_GET['self']) ){
					$variables ['alertMessage'] = "E-Mail-Adresse bereits mit anderem Namen/Zug in Verwendung!";
					$exit = true;
				}
			}
		}
	}
	
	if (! $exit) {
		/*
		 * logic to create or update existing user (with password)
		 * 
		 * if: user is updated by admin (uuid is parameter): update
		 * else if: user is updated by himself (self is parameter): update
		 * else: insert new user
		 */
		$engineObj = $engineDAO->getEngine($engine);
		$user = new User();
		$user->setUserData($firstname, $lastname, $email, $engineObj, $employerAddress, $employerMail);
		
		if( isset($_GET['uuid']) ){
			$user->setUuid($_GET['uuid']);
		} else if( isset($_GET['self']) ){
			$user->setUuid($_SESSION ['intranet_userid']);
		} else {
			$user->setPassword($password);
			
			// add default privileges to new user
			$privileges = $privilegeDAO->getPrivileges();
			foreach($privileges as $privilege){
				if($privilege->getIsDefault()){
					$user->addPrivilege($privilege);
				}
			}
		}
		$user = $userDAO->save($user);
		
		if ($user) {
			$variables['user'] = $user;
			/*
			 * update privileges if privilege-list is displayed (param updateRights)
			 * delete all from user and set all marked privileges
			 */
			if(isset($_POST['updateRights'])){
				$newPrivileges = array();
				foreach($privileges as $privilege){
					
					$inputName = "priv_" . $privilege->getUuid();
					if(isset ( $_POST [ $inputName ] )){
						$newPrivileges [] = $privilege;
					}
				}
				$user->resetPrivileges($newPrivileges);
				$userDAO->save($user);
			}
			
			if( isset($_GET['uuid']) || isset($_GET['self']) ){
				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserUpdated, $user->getUuid()));
				$variables ['successMessage'] = "Der Benutzer wurde aktualisiert";
			} else {
				$mail = mail_add_user($email, $password);
				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserCreated, $user->getUuid()));
				$variables ['successMessage'] = "Der Benutzer wurde angelegt und informiert";
				unset($_POST);
				unset($variables['user']);
				if(!$mail){
					$variables ['alertMessage'] = "E-Mail konnte nicht versendet werden";
				}
			}
			
			if(userLoggedIn()){
				//header ( "Location: " . $config["urls"]["intranet_home"] . "/users" ); // redirects
			}else{
				//header ( "Location: " . $config["urls"]["intranet_home"] . "/login" ); // redirects
			}
		} else {
			$variables ['alertMessage'] = "Ein unbekannter Fehler ist aufgetreten";
		}
	}
}

renderLayoutWithContentFile ($config["apps"]["landing"], "userEdit_template.php", $variables );
?>