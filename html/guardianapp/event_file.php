<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_staffpositions.php";
require_once LIBRARY_PATH . "/util.php";
require_once LIBRARY_PATH . "/file_create_report.php";


if(isset($_GET['render'])){
    renderPDF($_GET['event']);
    
} else {
        if(isset($_GET['event'])){
            
		$uuid = $_GET['event'];
            
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
		showAlert("Wach-ID nicht übergeben");
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
        'title' => "Wache",
        'event' => get_event($_GET['event']),
    	'staff' => get_staff($_GET['event']),
    	'print' => TRUE,
    	'orientation' => 'portrait'
    );
    
    renderPrintContentFile($config["apps"]["guardian"], "eventDetails/eventPrint_template.php", $variables);
}
