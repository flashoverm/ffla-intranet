<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'title' => "Übersicht Benutzer",
		'secured' => true,
		'privilege' => PORTALADMIN
);

if (isset ( $_POST ['disable'] )) {
	$uuid = trim ( $_POST ['disable'] );
	if($uuid == $_SESSION ['intranet_userid']){
		$variables ['alertMessage'] = "Eigenes Konto kann nicht gesperrt werden";
	} else if(lock_user ( $uuid )) {
		insert_log(LogbookActions::UserLocked, $uuid);
		$variables ['successMessage'] = "Benutzer gesperrt";
	} else {
		$variables ['alertMessage'] = "Benutzer sperren fehlgeschlagen";
	}
}
if (isset ( $_POST ['enable'] )) {
	$uuid = trim ( $_POST ['enable'] );
	if(unlock_user ( $uuid )){
		insert_log(LogbookActions::UserUnlocked, $uuid);
		$variables ['successMessage'] = "Benutzer freigegeben";
	} else {
		$variables ['alertMessage'] = "Benutzer freigeben fehlgeschlagen";
	}
}

if (isset ( $_POST ['resetpw'] )) {
	$resetpw_user_uuid = trim ( $_POST ['resetpw'] );
	$password = reset_password ( $resetpw_user_uuid );
	if($password){
		$mail = mail_reset_password ( $resetpw_user_uuid, $password );
		insert_log(LogbookActions::UserResetPassword, $uuid);
		$variables ['successMessage'] = "Passwort zurückgesetzt";
		if(!$mail){
			$variables ['alertMessage'] = "E-Mail konnte nicht versendet werden";
		}
	} else {
		$variables ['alertMessage'] = "Passwort konnte nicht zurückgesetzt werden";
	}
}

if (isset ( $_POST ['setpw'] )) {
	$setpw_user_uuid = trim ( $_POST ['setpw'] );
	$password = reset_password ( $setpw_user_uuid );
	if($password){
		$mail = mail_add_user(get_user($setpw_user_uuid)->email, $password);
		insert_log(LogbookActions::UserAddedPassword, $uuid);
		$variables ['successMessage'] = "Der Benutzer wurde angelegt und informiert";
		if(!$mail){
			$variables ['alertMessage'] = "E-Mail konnte nicht versendet werden";
		}
	} else {
		$variables ['alertMessage'] = "Der Benutzer konnte nicht angelegt werden";
	}
}

if(isset($_GET['filter'])){
	$variables ['title'] = 'Rechte-Gruppe ' . $_GET['filter'];
	$user = get_users_with_privilege ($_GET['filter']);
	$variables ['infoMessage'] = "Es werden nur Benutzer mit Recht '" . $_GET['filter'] . "' angezeigt! <a href='" . $config["urls"]["intranet_home"] . "/privilege'>Zurück zur Auswahl</a>";
} else {
	$user = get_users ();
}


$variables ['user'] = $user;



renderLayoutWithContentFile ($config["apps"]["landing"], "userOverview_template.php", $variables );

?>