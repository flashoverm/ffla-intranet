<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Prüfbericht",
    'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER
);

if(isset($_GET['inspection'])){
    
    $inspection = $inspectionDAO->getInspection($_GET['inspection']);
    
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
	
	$variables['orientation'] = 'landscape';
	renderPrintContentFile($config["apps"]["hydrant"], "inspectionDetails/inspectionPDF_template.php", $variables);
	
} else if( isset($_GET['inspection']) && isset($_GET['file']) ) {
	
	$uuid = $_GET['inspection'];
	$fullpath = $config["paths"]["inspections"] . basename($uuid) . ".pdf";
	$dl_filename = "Hydrantenprüfung_" . $uuid . ".pdf";
	getFileResponse($fullpath, "createInspectionFile", $uuid, $dl_filename);
	
} else {
	
	renderLayoutWithContentFile($config["apps"]["hydrant"], "inspectionDetails/inspectionDetails_template.php", $variables);
}

