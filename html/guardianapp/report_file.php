<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_report.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_staffpositions.php";
require_once LIBRARY_PATH . "/util.php";
require_once LIBRARY_PATH . "/file_create_report.php";


if(isset($_GET['render'])){
    renderPDF($_GET['report']);
    
} else {
	if(	userLoggedIn() ){
    	if(!current_user_has_privilege(EVENTMANAGER)){
            showAlert("Sie haben keine Berechtigung diese Seite anzuzeigen");
        } else if(isset($_GET['report'])){
            
            $uuid = $_GET['report'];
            
            $fullpath = $config["paths"]["reports"] . basename($uuid) . ".pdf";
            
            $error = false;
            if (!file_exists($fullpath) || isset($_GET['force'])) {
            	$error = createReportFile($uuid);
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
    header('Content-Disposition: inline; filename="Wachbericht_' . $uuid . '"');
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
    
    $variables = array(
        'title' => "Wachbericht",
        'report' => get_report($_GET['report']),
        'units' => get_report_units($_GET['report']),
    	'orientation' => 'portrait'
    );
    
    renderPrintContentFile($config["apps"]["guardian"], "reportDetails/reportPDF_template.php", $variables);
}
