<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_hydrant.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Zug nicht festgelegt",
    'secured' => true
);

if (isset($_GET['engine'])) {

    $engine = trim($_GET['engine']);
    
	$hydrants = get_hydrants_of_engine($engine);
    
    $variables ['hydrants'] = $hydrants;
    
    $mapUrl = $config["mapView"]["apiUrl"]
    . "?key=" . $config["mapView"]["apiKey"]
    . "&size=" . $config["mapView"]["widewidth"] . "x" . $config["mapView"]["height"]
    . "&scale=" . $config["mapView"]["retina"]
    . "&maptype=" . $config["mapView"]["maptype"];
    
    $mapUrl = $mapUrl . "&markers=color:red|label:" . "H";
    foreach ( $hydrants as $hydrant ) {
        //$mapUrl = $mapUrl . "&markers=color:red|label:" . $hydrant->hy . "|" . $hydrant->lat . "," . $hydrant->lng . "";  //hy as label does not work!
        $mapUrl = $mapUrl . "|" . $hydrant->lat . "," . $hydrant->lng . "";
    }
   
    if(isset($_GET['location'])) {
        $mapUrl = $mapUrl
        . "&markers=color:green|label:P|"
            . $_GET['location']
            . "";            
    }
    
    $variables ['mapURL'] = $mapUrl;
    $variables ['title'] = "Hydranten";
    $variables ['subtitle'] = get_engine($engine)->name;
} else {
    $variables ['alertMessage'] = "Zug nicht festgelegt";
}
  
renderLayoutWithContentFile($config["apps"]["hydrant"], "hydrantEngineView_template.php", $variables);
