<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'title' => "Logbuch",
		'secured' => true,
		'privilege' => Privilege::PORTALADMIN
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if( isset($_POST['purge']) ){
		$logbookDAO->clearLogbook();
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::LogbookDeleted, NULL));
	}
}

$variables ['resultSize'] = 20;

if(isset($_GET['page'])){
	$variables ['currentPage'] = $_GET['page'];
	$variables ['logbook'] = $logbookDAO->getLogbookPage($_GET['page'], $variables ['resultSize']);
} else {
	$variables ['currentPage'] = 1;
	$variables ['logbook'] = $logbookDAO->getLogbookPage(1, $variables ['resultSize']);
}

renderLayoutWithContentFile ($config["apps"]["landing"], "logbook_template.php", $variables );

?>