<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["employer"],
		'template' => "confirmationPrint_template.php",
		'title' => "Arbeitgeberbestätigung",
		'secured' => true,
		'orientation' => 'portrait'
);
checkSitePermissions($variables);

if (isset($_GET['id'])) {
	
	$confirmation = $confirmationDAO->getConfirmation($_GET['id']);
	$variables['confirmation'] = $confirmation;
	
	checkPermissions(array(
			array("privilege" => Privilege::FFADMINISTRATION),
			array("user" => $confirmation->getUser())
	), $variables);
}

if(isset($_GET['print'])){
	
	$variables['orientation'] = 'portrait';
	$variables['noHeader'] = 'true';
	renderPrintContentFile($variables);
	
} else if( isset($_GET['id']) && isset($_GET['file']) ) {
	
	$uuid = $_GET['id'];
	$fullpath = $config["paths"]["confirmations"] . basename($uuid) . ".pdf";
	$dl_filename = "Arbeitegeberbestaetigung_" . $uuid . ".pdf";
	getFileResponse($fullpath, "createConfirmationFile", $uuid, $dl_filename);
	
}
