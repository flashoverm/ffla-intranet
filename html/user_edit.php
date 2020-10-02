<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/mail_controller.php";

require_once LIBRARY_PATH . "/class/User.php";

$privileges = get_all_privileges();

// Pass variables (as an array) to template
$variables = array (
	'secured' => true,
	'engines' => get_engines(),
	'privileges' => $privileges,
);

if( isset($_GET['self']) ){
	// edit by user itself
	$variables['title'] = "Benutzer bearbeiten";
	
	$variables['showRights'] = false;
	$variables['user'] = get_user($_SESSION ['intranet_userid']);
	$variables['privilege'] = EDITUSER;
	
	
} else if( isset($_GET['uuid']) ){
	// edit by admin
	$variables['title'] = "Benutzer bearbeiten";
	$variables['privilege'] = PORTALADMIN;
	
	$variables['showRights'] = true;
	$user = get_user($_GET['uuid']);
	if($user){
		$variables['user'] = $user;
	} else {
		$variables ['alertMessage'] = "Benutzer wurde nicht gefunden";
	}
	
} else {
	// new user
	$variables['title'] = "Benutzer anlegen";
	if( $config ["settings"] ["selfregistration"]){
		$variables['secured'] = false;
		
		$variables['showRights'] = false;
	} else {
		$variables['privilege'] = PORTALADMIN;
		
		$variables['showRights'] = true;
	}
	
}



if (isset ( $_POST ['useremail'] ) && isset ( $_POST ['engine'] ) && isset ( $_POST ['firstname'] ) && isset ( $_POST ['lastname'] )) {

	$firstname = trim($_POST ['firstname']);
	$lastname = trim($_POST ['lastname']);
	$email = strtolower(trim($_POST ['useremail']));
	$engine = trim($_POST ['engine']);	
	
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
		if (email_in_use ( $email )) {
			$user = get_user_by_data($firstname, $lastname, $email, $engine);
			if($user){
				if($user->password == NULL){
					if(reset_password($user->uuid)){
						$variables ['successMessage'] = "Das Passwort wurde gesetzt und gesendet";
						insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::UserResetPassword, $user->uuid));
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
		if( isset($_GET['uuid']) ){
			$user = update_user($_GET['uuid'], $firstname, $lastname, $email, $engine );
		} else if( isset($_GET['self']) ){
			$user = update_user($_SESSION ['intranet_userid'], $firstname, $lastname, $email, $engine );
		} else {
			$user = insert_user ( $firstname, $lastname, $email, $password, $engine );
			// add default privileges to new user
			$privileges = get_all_privileges();
			foreach($privileges as $privilege){
				if($privilege->is_default){
					add_privilege_to_user($user->uuid, $privilege->uuid);
				}
			}
		}
		
		if ($user) {
			$variables['user'] = $user;
			/*
			 * update privileges if privilege-list is displayed (param updateRights)
			 * delete all from user and set all marked privileges
			 */
			if(isset($_POST['updateRights'])){
				foreach($privileges as $privilege){
					
					remove_privilege_from_user($user->uuid, $privilege->uuid);
					
					$inputName = "priv_" . $privilege->uuid;
					
					if(isset ( $_POST [ $inputName ] )){
						add_privilege_to_user($user->uuid, $privilege->uuid);
					}
				}
			}
			
			if( isset($_GET['uuid']) || isset($_GET['self']) ){
				insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::UserUpdated, $user->uuid));
				$variables ['successMessage'] = "Der Benutzer wurde aktualisiert";
			} else {
				$mail = mail_add_user($email, $password);
				insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::UserCreated, $user->uuid));
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