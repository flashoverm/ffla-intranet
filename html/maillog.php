<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_maillog.php";

// Pass variables (as an array) to template
$variables = array (
		'title' => "Mail Log",
		'secured' => true,
		'privilege' => PORTALADMIN
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if( isset($_POST['purge']) ){
		clear_maillog();		
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
	$variables ['mails'] = get_maillogs($_GET['page'], $variables ['resultSize']);
} else {
	$variables ['currentPage'] = 1;
	$variables ['mails'] = get_maillogs(1, $variables ['resultSize']);
}

renderLayoutWithContentFile ($config["apps"]["landing"], "maillog_template.php", $variables );
 