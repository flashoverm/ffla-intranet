<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

$privileges = get_all_privileges();

// Pass variables (as an array) to template
$variables = array (
		'secured' => true,
		'privilege' => PORTALADMIN,
		'title' => "Rechteverwaltung",
		'user' => get_users(),
		'privileges' => $privileges,
);

if (isset ( $_POST ['user'] ) ) {
	
	$user = trim ( $_POST ['user'] );
	
	$ok = true;
	foreach($privileges as $privilege){

		remove_privilege_from_user($user, $privilege->uuid);
		
		$inputName = "priv_" . $privilege->uuid;
		
		if(isset ( $_POST [ $inputName ] )){
			$ok = $ok && add_privilege_to_user($user, $privilege->uuid);
		}
	}
	
	if($ok){
		$variables ['successMessage'] = "Benutzerrechte gespeichert";
	} else {
		$variables ['alertMessage'] = "Benutzerrechte konnten nicht gespeichert werden";
	}
}
	
renderLayoutWithContentFile ($config["apps"]["landing"], "privilege_template.php", $variables );
