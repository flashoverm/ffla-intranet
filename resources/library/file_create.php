<?php

function createReportFile($uuid){
	global $config;
	
	$urlpath = $config ["urls"] ["guardianapp_home"] . "/reports/" . $uuid . "/print";
	
	$outfile = $config["paths"]["reports"] . "/" . $uuid . '.pdf';
	
	return createFile(true, $urlpath, $outfile);
}

function createInspectionFile($uuid){
	global $config;
	
	$urlpath = $config['urls']['hydrantapp_home'] . "/inspection/" . $uuid . "/print";
	
	$outfile = $config["paths"]["inspections"] . "/" . $uuid . '.pdf';

	return createFile(false, $urlpath, $outfile);
}

function createConfirmationFile($uuid){
	global $config;
	
	$urlpath = $config['urls']['employerapp_home'] . "/confirmations/" . $uuid . "/print";
	
	$outfile = $config["paths"]["confirmations"] . "/" . $uuid . '.pdf';
	
	return createFile(true, $urlpath, $outfile);
}

function createFile($portrait, $urlpath, $outputfile){
	global $config;
		
	$nodePath = $config["paths"]["nodejs"];
	
	if ( ! file_exists( dirname ($outputfile) ) ) {
		mkdir(dirname ($outputfile), 0755, true);
	}
	
	if($portrait){
		$jsfile = "topdf_portrait.js";
	} else {
		$jsfile = "topdf.js";
	}
	$jsPath = LIBRARY_PATH . "/puppeteer/" . $jsfile;
	
	$url = $config["urls"]["base_url"] . $urlpath;

	//echo $nodePath . " " . $jsPath . " " . $url . " " . $outputfile;
	$error = exec($nodePath . " " . $jsPath . " " . $url . " " . $outputfile);
	
	if($error == "Done"){
		return false;
	}
	return $error;
}

function getFileResponse($filepath, $creationCallBack, $uuid, $filename){
	$error = false;
	
	if (!file_exists($filepath) || isset($_GET['force'])) {
		$error = $creationCallBack($uuid);
	}
	
	if($error){
		echo $error;
	} else {
		prepareResponse($filepath, $filename);
	}
}

function prepareResponse($fullpath, $filename){
	header("Content-type: application/pdf");
	header('Content-Disposition: inline; filename="' . $filename . '"');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Content-Length: ' . filesize($fullpath));
	header('Pragma: public');
	ob_clean();
	flush();
	readfile($fullpath);
}