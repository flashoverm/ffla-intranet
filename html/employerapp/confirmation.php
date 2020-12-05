<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Arbeitgeberbestätigung",
		'secured' => true,
		'orientation' => 'portrait'
);

if (isset($_GET['id'])) {
	
	$confirmation = get_confirmation($_GET['id']);
	$user = get_user($confirmation->user);
	
	$variables['confirmation'] = $confirmation;
	$variables['user'] = $user;
}

if(isset($_GET['print'])){
	
	$variables['orientation'] = 'portrait';
	renderPrintContentFile($config["apps"]["employer"], "confirmationPrint_template.php", $variables, true);
	
} else if( isset($_GET['id']) && isset($_GET['file']) ) {
	
	$uuid = $_GET['id'];
	$fullpath = $config["paths"]["confirmations"] . basename($uuid) . ".pdf";
	$dl_filename = "Arbeitegeberbestaetigung_" . $uuid . ".pdf";
	getFileResponse($fullpath, "createConfirmationFile", $uuid, $dl_filename);
	
}
