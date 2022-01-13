<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["administration"],
		'template' => "logbook_template.php",
		'title' => "Logbuch",
		'secured' => true,
		'privilege' => Privilege::PORTALADMIN
);
checkSitePermissions($variables);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if( isset($_POST['purge']) ){
		$logbookDAO->clearLogbook();
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::LogbookDeleted, NULL));
	}
}


if(isset($_GET['user'])){
	$user = $userDAO->getUserByUUID($_GET['user']);
	if($user){
		$variables ['forUser'] = $user;
		$variables ['logbook'] = $logbookDAO->getLogbookByUser($user->getUuid(), $_GET);
	} else {
		$variables ['alertMessage'] = "Benutzer " . $_GET['user'] . " nicht gefunden";
		$variables ['logbook'] = $logbookDAO->getLogbook($_GET);
	}
	
} else {
	$variables ['logbook'] = $logbookDAO->getLogbook($_GET);
}


renderLayoutWithContentFile ($variables );

?>