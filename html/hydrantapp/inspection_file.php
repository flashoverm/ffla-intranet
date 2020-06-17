<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_inspection.php";
require_once LIBRARY_PATH . "/util.php";
require_once LIBRARY_PATH . "/inspection_file_create.php";

if(isset($_GET['render']) && $_SERVER['HTTP_HOST'] == "localhost"){
    renderPDF($_GET['inspection']);
    
} else {
	if(	userLoggedIn() ){
        if( ! current_user_has_privilege(ENGINEHYDRANTMANANGER)){
            showAlert("Sie haben keine Berechtigung diese Seite anzuzeigen");
        } else if(isset($_GET['inspection'])){
            
            $uuid = $_GET['inspection'];
            
            $fullpath = $config["paths"]["inspections"] . basename($uuid) . ".pdf";
            
            $error = false;
            if (!file_exists($fullpath) || isset($_GET['force'])) {
                $error = createInspectionFile($uuid);
            }
            
            if($error){
                echo $error;
            } else {
                prepareResponse($fullpath, $uuid);
            }
        } else {
            showAlert("Prüfberichts-ID nicht übergeben");
        }
    } else {
        goToLogin();
    }
}

function prepareResponse($fullpath, $uuid){
    header("Content-type: application/pdf");
    header('Content-Disposition: inline; filename="Prüfbericht_' . $uuid . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Content-Length: ' . filesize($fullpath));
    header('Pragma: public');
    ob_clean();
    flush();
    readfile($fullpath);
}

function renderPDF(){
    global $config;
    global $hydrant_criteria;
    
    $variables = array(
        'title' => "Prüfbericht",
        'inspection' => get_inspection($_GET['inspection']),
        'criteria' => $hydrant_criteria
    );
    
    renderContentFile($config["apps"]["hydrant"], "inspectionDetails/inspectionPDF_template.php", $variables);
}