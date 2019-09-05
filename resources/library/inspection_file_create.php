<?php
require_once realpath(dirname(__FILE__) . "/../config.php");

function createInspectionFile($uuid){
    global $config;
    
    $nodePath = $config["paths"]["nodejs"];
    
    $jsPath = LIBRARY_PATH . "/puppeteer/topdf.js";
    
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://localhost" . $config['urls']['hydrantapp_home'] . "/inspection/file/" . $uuid . "/render";
   
    $outfile = $config["paths"]["inspections"] . "/" . $uuid . '.pdf';
                
    $error = exec($nodePath . " " . $jsPath . " " . $url . " " . $outfile);
    
    if($error == "Done"){
        return false;
    }
    return $error;
}
