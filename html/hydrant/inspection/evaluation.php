<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "inspectionEvaluation_template.php",
	    'title' => "Auswertung Hydrantenprüfungen",
	    'secured' => true,
		'privilege' => Privilege::HYDRANTADMINISTRATOR
);
$variables = checkSitePermissions($variables);

$variables['years'] = $inspectionDAO->getInspectionYears();

if(isset($_GET['year'])){
	$variables['year'] = $_GET['year'];
	$variables['engineEntries'] = $inspectionDAO->getHydrantsByYear($variables['year']);
	
} else {
	$variables['year'] = $variables['years'][0];
	$variables['engineEntries'] = $inspectionDAO->getHydrantsByYear($variables['year']);
}

renderLayoutWithContentFile($variables);
