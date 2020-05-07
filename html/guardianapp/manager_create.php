<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/mail_controller.php";

$engines = get_engines ();

// Pass variables (as an array) to template
$variables = array (
    'title' => "Wachbeauftragten anlegen",
    'secured' => true,
	'engines' => $engines,
	'privilege' => EVENTADMIN
);

if (isset ( $_POST ['email'] ) && isset ( $_POST ['engine'] ) && isset ( $_POST ['firstname'] ) && isset ( $_POST ['lastname'] )) {

	$firstname = trim($_POST ['firstname']);
	$lastname = trim($_POST ['lastname']);
	$email = strtolower(trim($_POST ['email']));
	$engine = trim($_POST ['engine']);
	
	if (email_in_use ( $email )) {
		$user = get_user_by_data($firstname, $lastname, $email, $engine);
		
		if($user){
			
			if(user_has_privilege($user->uuid, EVENTMANAGER)){
				$variables ['successMessage'] = 'Dieser Benutzer ist bereits Wachbeauftragter';
				
			} else {
				$variables ['successMessage'] = 'Diese E-Mail-Adresse ist bereits angelegt. Der Benutzer wurde zum Wachbeauftragten ernannt';
				
				add_privilege_to_user($user->uuid, EVENTMANAGER);
				reactivate_user($user->uuid);
				$password = reset_password($user->uuid);
				
				//Send mail
			}

		} else {
			$variables ['alertMessage'] = 'Diese E-Mail-Adresse wird bereits von einem anderen Benutzer verwendet - Die eingegebenen Daten stimmen nicht überein';
		}
		
	} else {

		$password = random_password ();
		$result = insert_manager ( $firstname, $lastname, $email, $password, $engine );

		if ($result) {
			$mail = mail_add_manager ( $email, $password );
			$variables ['successMessage'] = 'Wachbeauftragter erfolgreich angelegt - <a href="' . $config["urls"]["guardianapp_home"] . '/manager" class="alert-link">Zurück zur Übersicht</a>';
			if(!$mail){
				$variables ['alertMessage'] = 'E-Mail mit Zugangsdaten konnte nicht versendet werden';
			}
		} else {
		    $variables ['alertMessage'] = 'Beim Abspeichern ist leider ein Fehler aufgetreten';
		}
	}
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "managerCreate_template.php", $variables );