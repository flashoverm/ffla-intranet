<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_hydrant.php";
require_once LIBRARY_PATH . "/db_inspection.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Hydrantenprüfungen",
    'secured' => true,
    'right' => ENGINEHYDRANTMANANGER
);

$engine = get_engine_of_user($_SESSION ['intranet_userid']);

if(isset($_POST['delete'])){
    
    if(delete_inspection($_POST['delete'])){
        $variables ['successMessage'] = "Prüfbericht gelöscht";
    } else {
        $variables ['alertMessage'] = "Prüfbericht konnte nicht gelöscht werden";
    }
}

if(get_engine($engine)->isadministration){
    $variables ['inspections'] = get_inspections();
} else {
    $variables ['inspections'] = get_inspections_of_engine($engine);
}

renderLayoutWithContentFile($config["apps"]["hydrant"], "inspectionOverview_template.php", $variables);
