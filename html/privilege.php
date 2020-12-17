<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

$privileges = $privilegeDAO->getPrivileges();

// Pass variables (as an array) to template
$variables = array (
		'secured' => true,
		'privilege' => Privilege::PORTALADMIN,
		'title' => "Rechteverwaltung",
		'user' => $userDAO->getUsers(),
		'privileges' => $privileges,
);

if (isset ( $_POST ['user'] ) ) {
	
	$user = $userDAO->getUserByUUID(trim ( $_POST ['user'] ));
	
	$ok = true;
	$newPrivileges = array();
	foreach($privileges as $privilege){

		$inputName = "priv_" . $privilege->getUuid();
		if(isset ( $_POST [ $inputName ] )){
			$newPrivileges [] = $privilege;
		}
	}
	$user->resetPrivileges($newPrivileges);
	$ok = $userDAO->save($user);
		
	if($ok){
		$variables ['successMessage'] = "Benutzerrechte gespeichert";
	} else {
		$variables ['alertMessage'] = "Benutzerrechte konnten nicht gespeichert werden";
	}
}
	
renderLayoutWithContentFile ($config["apps"]["landing"], "privilege_template.php", $variables );
