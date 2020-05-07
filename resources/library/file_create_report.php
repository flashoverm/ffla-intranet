<?php
require_once realpath(dirname(__FILE__) . "/../config.php");

function createReportFile($uuid){
	global $config;
	
	$nodePath = $config["paths"]["nodejs"];
	
	$jsPath = LIBRARY_PATH . "/puppeteer/topdf_portrait.js";
	
	$url = $config['urls']['baseUrl'] . $config ["urls"] ["guardianapp_home"] . "/reports/file/" . $uuid . "/render";
	
	$outfile = $config["paths"]["reports"] . "/" . $uuid . '.pdf';
	
	$error = exec($nodePath . " " . $jsPath . " " . $url . " " . $outfile);
	
	if($error == "Done"){
		return false;
	}
	return $error;
}