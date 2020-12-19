<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'title' => "Übersicht Benutzer",
		'secured' => true,
		'privilege' => Privilege::PORTALADMIN
);

if (isset ( $_POST ['disable'] )) {
	$uuid = trim ( $_POST ['disable'] );
	if($uuid == $_SESSION ['intranet_userid']){
		$variables ['alertMessage'] = "Eigenes Konto kann nicht gesperrt werden";
	} else if(lock_user ( $uuid )) {
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserLocked, $uuid));
		$variables ['successMessage'] = "Benutzer gesperrt";
	} else {
		$variables ['alertMessage'] = "Benutzer sperren fehlgeschlagen";
	}
}
if (isset ( $_POST ['enable'] )) {
	$uuid = trim ( $_POST ['enable'] );
	if(unlock_user ( $uuid )){
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserUnlocked, $uuid));
		$variables ['successMessage'] = "Benutzer freigegeben";
	} else {
		$variables ['alertMessage'] = "Benutzer freigeben fehlgeschlagen";
	}
}
if (isset ( $_POST ['delete'] )) {
	$uuid = trim ( $_POST ['delete'] );
	if($uuid == $_SESSION ['intranet_userid']){
		$variables ['alertMessage'] = "Eigenes Konto kann nicht gelöscht werden";
	} else if(delete_user( $uuid )) {
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserDeleted, $uuid));
		$variables ['successMessage'] = "Benutzer gelöscht";
	} else {
		$variables ['alertMessage'] = "Benutzer löschen fehlgeschlagen";
	}
}

if (isset ( $_POST ['resetpw'] )) {
	$resetpw_user_uuid = trim ( $_POST ['resetpw'] );
	$password = reset_password ( $resetpw_user_uuid );
	if($password){
		$mail = mail_reset_password ( $resetpw_user_uuid, $password );
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserResetPassword, $uuid));
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
		$mail = mail_add_user($userDAO->getUserByUUID($setpw_user_uuid)->getEmail(), $password);
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserAddedPassword, $uuid));
		$variables ['successMessage'] = "Der Benutzer wurde angelegt und informiert";
		if(!$mail){
			$variables ['alertMessage'] = "E-Mail konnte nicht versendet werden";
		}
	} else {
		$variables ['alertMessage'] = "Der Benutzer konnte nicht angelegt werden";
	}
}

if(isset($_GET['filter'])){
	$privilege = $privilegeDAO->getPrivilege($_GET['filter']);
	$variables ['title'] = 'Rechte-Gruppe ' . $privilege->getPrivilege();
	$user = $userDAO->getUsersWithPrivilege($_GET['filter']);
	$variables ['infoMessage'] = "Es werden nur Benutzer mit Recht '" . $privilege->getPrivilege() . "' angezeigt! <a href='" . $config["urls"]["intranet_home"] . "/privilege'>Zurück zur Auswahl</a>";
} else {
	$user = $userDAO->getUsers();
}


$variables ['user'] = $user;
$variables ['deletedUser'] = $userDAO->getDeletedUsers();


renderLayoutWithContentFile ($config["apps"]["landing"], "userOverview_template.php", $variables );

?>