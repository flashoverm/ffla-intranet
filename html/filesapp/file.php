<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once LIBRARY_PATH . "/util.php";

session_start ();

if(	userLoggedIn() ){
	
	$fullpath = $config["paths"]["files"] . basename($_GET['file']);
	
	if (file_exists($fullpath)) {
		
		if(endsWith($_GET['file'], ".pdf")){
			header("Content-type: application/pdf");
			header('Content-Disposition: inline; filename=' . $_GET['file']);

		} else {
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $_GET['file']);
		}
		
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Content-Length: ' . filesize($fullpath));
		header('Pragma: public');
		ob_clean();
		flush();
		readfile($fullpath);
	}	
} else {
	goToLogin();
}

?>