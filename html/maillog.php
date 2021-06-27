<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["landing"],
		'template' => "maillog_template.php",
		'title' => "Mail Log",
		'secured' => true,
		'privilege' => Privilege::PORTALADMIN
);
$variables = checkPermissions($variables);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if( isset($_POST['purge']) ){
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::MaillogDeleted, NULL));
		$mailLogDAO->clearMailLog();
	}
	if( isset($_POST['testmail']) ){
		for($i=0; $i<10; $i++){
			send_mail("tst@mail.de", "Testmail", "This is a text mail...");
		}
	}
}

$variables ['resultSize'] = 20; 

if(isset($_GET['page'])){
	$variables ['currentPage'] = $_GET['page'];
	$variables ['mails'] = $mailLogDAO->getMailLogs($_GET['page'], $variables ['resultSize']);
} else {
	$variables ['currentPage'] = 1;
	$variables ['mails'] = $mailLogDAO->getMailLogs(1, $variables ['resultSize']);
}

renderLayoutWithContentFile ($variables );
 