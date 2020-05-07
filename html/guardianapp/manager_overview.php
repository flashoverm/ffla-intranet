<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'title' => "Übersicht Wachbeauftragte",
		'secured' => true,
		'privilege' => EVENTADMIN
);

if (isset ( $_POST ['disable'] )) {
	$manager_uuid = trim ( $_POST ['disable'] );
	if($manager_uuid == $_SESSION ['guardian_userid']){
		$variables ['alertMessage'] = "Eigenes Konto kann nicht deaktiviert werden";
	} else if(deactivate_user ( $manager_uuid )) {
		$variables ['successMessage'] = "Wachbeauftragter deaktiviert";	
	} else {
		$variables ['alertMessage'] = "Deaktivieren des Wachbeauftragten fehlgeschlagen";
	}
}
if (isset ( $_POST ['enable'] )) {
	$manager_uuid = trim ( $_POST ['enable'] );
	if($manager_uuid == $_SESSION ['guardian_userid']){
		$variables ['alertMessage'] = "Eigenes Konto kann nicht aktiviert werden";
	} else if(reactivate_user ( $manager_uuid )){
		$variables ['successMessage'] = "Wachbeauftragter aktiviert";
	} else {
		$variables ['alertMessage'] = "Aktivieren des Wachbeauftragten fehlgeschlagen";
	}
}

if (isset ( $_POST ['resetpw'] )) {
	$resetpw_manager_uuid = trim ( $_POST ['resetpw'] );
	$password = reset_password ( $resetpw_manager_uuid );
	if($password){
		$mail = mail_reset_password ( $resetpw_manager_uuid, $password );
		$variables ['successMessage'] = "Passwort zurückgesetzt";
		if(!$mail){
			$variables ['alertMessage'] = "E-Mail konnte nicht versendet werden";
		}
	} else {
		$variables ['alertMessage'] = "Passwort konnte nicht zurückgesetzt werden";
	}
}
$manager = get_all_manager ();
$variables ['manager'] = $manager;

renderLayoutWithContentFile ($config["apps"]["guardian"], "managerOverview_template.php", $variables );

?>