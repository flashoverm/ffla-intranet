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
$variables = checkPermissions($variables);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if( isset($_POST['purge']) ){
		$logbookDAO->clearLogbook();
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::LogbookDeleted, NULL));
	}
}

$variables ['logbook'] = $logbookDAO->getLogbookPage($_GET);

renderLayoutWithContentFile ($variables );

?>