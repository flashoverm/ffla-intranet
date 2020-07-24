<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_hydrant.php";
require_once LIBRARY_PATH . "/db_inspection.php";

require_once LIBRARY_PATH . "/class/HydrantInspection.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Prüfbericht",
    'secured' => true,
    'privilege' => ENGINEHYDRANTMANANGER
);

if(isset($_GET['inspection'])){
    
    $inspection = get_inspection($_GET['inspection']);
    
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

$variables['criteria'] = $hydrant_criteria;

renderLayoutWithContentFile($config["apps"]["hydrant"], "inspectionDetails/inspectionDetails_template.php", $variables);

//renderContentFile($config["apps"]["hydrant"], "inspectionDetails/inspectionPDF_template.php", $variables);