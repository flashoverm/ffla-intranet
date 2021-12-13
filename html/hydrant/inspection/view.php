<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "inspectionDetails/inspectionDetails_template.php",
	    'title' => "Prüfbericht",
	    'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER
);
$variables = checkSitePermissions($variables);

if(isset($_GET['id'])){
    
    $inspection = $inspectionDAO->getInspection($_GET['id']);
    
    if($inspection) {
        $variables['inspection'] = $inspection;
    } else {
        $variables ['alertMessage'] = "Prüfbericht existiert nicht";
        $variables ['showFormular'] = false;
    }
} else {
    $variables ['alertMessage'] = "Prüfbericht kann nicht angezeigt werden";
    $variables ['showFormular'] = false;
}

$variables['criteria'] = InspectedHydrant::HYDRANTCRITERIA;

if(isset($_GET['print'])){
	
	$variables['app'] = $config["apps"]["hydrant"];
	$variables['template'] = "inspectionDetails/inspectionPDF_template.php";
	$variables['orientation'] = 'landscape';
	renderPrintContentFile($variables);
	
} else if( isset($_GET['id']) && isset($_GET['file']) ) {
	
	$uuid = $_GET['id'];
	$fullpath = $config["paths"]["inspections"] . basename($uuid) . ".pdf";
	$dl_filename = "Hydrantenprüfung_" . $uuid . ".pdf";
	getFileResponse($fullpath, "createInspectionFile", $uuid, $dl_filename);
	
} else {
	renderLayoutWithContentFile($variables);
}

