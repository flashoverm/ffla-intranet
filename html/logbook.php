<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_logbook.php";

// Pass variables (as an array) to template
$variables = array (
		'title' => "Logbuch",
		'secured' => true,
		'privilege' => PORTALADMIN
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if( isset($_POST['purge']) ){
		insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::LogbookDeleted, NULL));
		clear_logbook();
	}
}

$variables ['resultSize'] = 20;

if(isset($_GET['page'])){
	$variables ['currentPage'] = $_GET['page'];
	$variables ['logbook'] = get_logbook_page($_GET['page'], $variables ['resultSize']);
} else {
	$variables ['currentPage'] = 1;
	$variables ['logbook'] = get_logbook_page(1, $variables ['resultSize']);
}

renderLayoutWithContentFile ($config["apps"]["landing"], "logbook_template.php", $variables );

?>