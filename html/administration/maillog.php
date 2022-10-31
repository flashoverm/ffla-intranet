<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["administration"],
		'template' => "maillog_template.php",
		'title' => "Mail Log",
		'secured' => true,
		'privilege' => Privilege::PORTALADMIN
);
checkSitePermissions($variables);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if( isset($_POST['purge']) ){
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::MaillogDeleted, NULL));
		$mailLogDAO->clearMailLog();
	}
	if( isset($_POST['testmail']) ){
		for($i=0; $i<2; $i++){
			send_mail("markus@thral.de", "Testmail", "This is a text mail...");
		}
	}
}

$variables ['mails'] = $mailLogDAO->getMailLogs($_GET);

renderLayoutWithContentFile ($variables );
 