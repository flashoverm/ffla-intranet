<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_privilege.php";
require_once LIBRARY_PATH . "/db_user.php";

$privileges = get_all_privileges();

// Pass variables (as an array) to template
$variables = array (
		'secured' => true,
		'privilege' => EVENTADMIN,	//TODO privilege: Global Admin
		'title' => "Rechteverwaltung",
		'user' => get_all_user(),
		'privileges' => $privileges,
);

if (isset ( $_POST ['user'] ) ) {
	
	$user = trim ( $_POST ['user'] );
	
	$ok = remove_privileges_from_user($user);
	
	if($ok){
		foreach($privileges as $privileg){
			
			$inputName = "priv_" . $privileg->name;
			
			if(isset ( $_POST [ $inputName ] )){
				$ok = $ok && add_privilege_to_user($user, $privileg->name);
			}
		}
	}
	
	if($ok){
		$variables ['successMessage'] = "Benutzerrechte gespeichert";
	} else {
		$variables ['alertMessage'] = "Benutzerrechte konnten nicht gespeichert werden";
	}
}
	
renderLayoutWithContentFile ($config["apps"]["landing"], "privilege_template.php", $variables );
