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
		'title' => "Benutzer anlegen",
		'secured' => true,
		'engines' => get_engines(),
		'privileges' => $privileges,
);

if( $config ["settings"] ["selfregistration"]){
	$variables['secured'] = false;
}

if(isset($_GET['uuid'])){
	$variables['privilege'] = PORTALADMIN;
	$variables['title'] = "Benutzer bearbeiten";
	$user = get_user($_GET['uuid']);
	if($user){
		$variables['user'] = $user;
	} else {
		$variables ['alertMessage'] = "Benutzer wurde nicht gefunden";
	}
}

if (isset ( $_POST ['useremail'] ) && isset ( $_POST ['engine'] ) && isset ( $_POST ['firstname'] ) && isset ( $_POST ['lastname'] )) {

	$firstname = trim($_POST ['firstname']);
	$lastname = trim($_POST ['lastname']);
	$email = strtolower(trim($_POST ['useremail']));
	$engine = trim($_POST ['engine']);	
	
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
		if (email_in_use ( $email )) {
			$user = get_user_by_data($firstname, $lastname, $email, $engine);
			if($user){
				if($user->password == NULL){
					if(reset_password($user->uuid)){
						$variables ['successMessage'] = "Der Benutzer wurde angelegt und informiert";
					} else {
						$variables ['alertMessage'] = "Ein unbekannter Fehler ist aufgetreten";
					}
					$exit = true;
				} else {
					if( ! isset($_GET['uuid'])){
						$variables ['alertMessage'] = "Diese E-Mail-Adresse ist bereits vergeben";
						$exit = true;
					}
				}
			} else {
				if(! isset($_GET['uuid'])){
					$variables ['alertMessage'] = "E-Mail-Adresse bereits mit anderem Namen/Zug in Verwendung! Bitte Eingaben kontrollieren oder Auswahl verwenden";
					$exit = true;
				}
			}
		}
	}
	if (! $exit) {
		if(isset($_GET['uuid'])){
			$user = update_user($_GET['uuid'], $firstname, $lastname, $email, $engine );
		} else {
			$user = insert_user ( $firstname, $lastname, $email, $password, $engine );
		}
		if ($user) {
			$variables['user'] = $user;
			if(isset($_POST['updateRights'])){
				foreach($privileges as $privilege){
					
					remove_privilege_from_user($user->uuid, $privilege->privilege);
					
					$inputName = "priv_" . $privilege->privilege;
					
					if(isset ( $_POST [ $inputName ] )){
						add_privilege_to_user($user->uuid, $privilege->privilege);
					}
				}
			}
			
			if(isset($_GET['uuid'])){
				$variables ['successMessage'] = "Der Benutzer wurde aktualisiert";
			} else {
				$mail = mail_add_user($email, $password);
				$variables ['successMessage'] = "Der Benutzer wurde angelegt und informiert";
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