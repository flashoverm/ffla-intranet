<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["users"],
		'template' => "userOverview_template.php",
		'title' => "Übersicht Benutzer",
		'secured' => true,
		'privilege' => Privilege::PORTALADMIN
);
checkSitePermissions($variables);

if (isset ( $_POST ['disable'] )) {
	$uuid = trim ( $_POST ['disable'] );
	if($uuid == SessionUtil::getCurrentUserUUID()){
		$variables ['alertMessage'] = "Eigenes Konto kann nicht gesperrt werden";
	} else if($userController->lockUser( $uuid )) {
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserLocked, $uuid));
		$variables ['successMessage'] = "Benutzer gesperrt";
	} else {
		$variables ['alertMessage'] = "Benutzer sperren fehlgeschlagen";
	}
}
if (isset ( $_POST ['enable'] )) {
	$uuid = trim ( $_POST ['enable'] );
	if($userController->unlockUser( $uuid )){
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserUnlocked, $uuid));
		$variables ['successMessage'] = "Benutzer freigegeben";
	} else {
		$variables ['alertMessage'] = "Benutzer freigeben fehlgeschlagen";
	}
}
if (isset ( $_POST ['delete'] )) {
	$uuid = trim ( $_POST ['delete'] );
	if($uuid == SessionUtil::getCurrentUserUUID()){
		$variables ['alertMessage'] = "Eigenes Konto kann nicht gelöscht werden";
	} else if($userController->deleteUser( $uuid )) {
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserDeleted, $uuid));
		$variables ['successMessage'] = "Benutzer gelöscht";
	} else {
		$variables ['alertMessage'] = "Benutzer löschen fehlgeschlagen";
	}
}

if (isset ( $_POST ['resetpw'] )) {
	$uuid = trim ( $_POST ['resetpw'] );
	$password = $userController->resetPassword( $uuid );
	if($password){
		$mail = mail_reset_password ( $uuid, $password );
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
	$uuid = trim ( $_POST ['setpw'] );
	$password = $userController->resetPassword ( $uuid );
	$userController->addPrivilegeForMainEngineToUserByName($uuid, Privilege::EDITUSER);
	if($password){
		$mail = mail_add_user($userDAO->getUserByUUID($uuid)->getEmail(), $password);
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
	$variables ['title'] = 'Rechte-Gruppe ' . $privilege->getPrivilege() . " <small>(" . $privilege->getDescription() . ")</small>";
	$user = $userDAO->getUsersWithPrivilege($_GET['filter']);
	$variables ['infoMessage'] = "Es werden nur Benutzer mit Recht '" . $privilege->getPrivilege() . "' angezeigt! <a href='" . $config["urls"]["intranet_home"] . "/users/privileges'>Zurück zur Auswahl</a>";
} else {
	$user = $userDAO->getUsers();
}


$variables ['user'] = $user;
$variables ['deletedUser'] = $userDAO->getDeletedUsers();


renderLayoutWithContentFile ($variables );

?>