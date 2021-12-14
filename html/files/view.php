<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once LIBRARY_PATH . "/util.php";

if(	SessionUtil::userLoggedIn() ){
	
	$fullpath = $config["paths"]["files"] . basename($_GET['id']);
	
	if (file_exists($fullpath)) {
		
		if(StringUtil::endsWith($_GET['file'], ".pdf")){
			header("Content-type: application/pdf");
			header('Content-Disposition: inline; filename=' . $_GET['id']);

		} else {
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $_GET['id']);
		}
		
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Content-Length: ' . filesize($fullpath));
		header('Pragma: public');
		ob_clean();
		flush();
		readfile($fullpath);
	} else {
		echo "Datei nicht gefunden";
	}
} else {
	SessionUtil::goToLogin();
}

?>