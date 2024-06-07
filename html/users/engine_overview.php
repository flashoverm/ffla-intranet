<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
	'app' => $config["apps"]["users"],
	'template' => "engineUserOverview_template.php",
	'title' => "Übersicht Benutzer des Löschzugs",
    'subtitle' => "Inklusive Benutzern mit zusätzlichem Löschzug",
	'secured' => true,
    'privilege' => Privilege::ENGINEMANAGER
);
checkSitePermissions($variables);

$variables ['title'] = "Übersicht Benutzer der Einheit \"" . $currentUser->getEngine()->getName() . "\"";

if(isset($_GET['filter'])){
    $privilege = $privilegeDAO->getPrivilege($_GET['filter']);
    $variables ['title'] = 'Rechte-Gruppe ' . $privilege->getPrivilege() . " <small>(" . $privilege->getDescription() . ")</small>";
    $user = $userDAO->getUsersWithPrivilegeByAllEngines($currentUser->getEngine()->getUuid(), $_GET['filter']);
    $variables ['infoMessage'] = "Es werden nur Benutzer mit Recht '" . $privilege->getPrivilege() . "' angezeigt! <a href='" . $config["urls"]["intranet_home"] . "/users/engine/privileges'>Zurück zur Auswahl</a>";
} else {
    $user = $userDAO->getUsersByAllEngines($currentUser->getEngine()->getUuid());
}

$variables ['user'] = $user;

renderLayoutWithContentFile ($variables );
