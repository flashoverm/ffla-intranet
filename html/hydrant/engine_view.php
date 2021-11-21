<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "hydrantEngineView_template.php",
	    'title' => "Zug nicht festgelegt",
	    'secured' => true
);
$variables = checkPermissions($variables);

if (isset($_GET['engine'])) {

    $engine = trim($_GET['engine']);
    
	$hydrants = $hydrantDAO->getHydrantsOfEngine($engine);
    
    $variables ['hydrants'] = $hydrants;
    
    $mapUrl = $config["mapView"]["apiUrl"]
    . "?key=" . $config["mapView"]["apiKey"]
    . "&size=" . $config["mapView"]["widewidth"] . "x" . $config["mapView"]["height"]
    . "&scale=" . $config["mapView"]["retina"]
    . "&maptype=" . $config["mapView"]["maptype"];
    
    $mapUrl = $mapUrl . "&markers=color:red|label:" . "H";
    foreach ( $hydrants as $hydrant ) {
        //$mapUrl = $mapUrl . "&markers=color:red|label:" . $hydrant->hy . "|" . $hydrant->lat . "," . $hydrant->lng . "";  //hy as label does not work!
        $mapUrl = $mapUrl . "|" . $hydrant->getLat() . "," . $hydrant->getLng() . "";
    }
   
    if(isset($_GET['location'])) {
        $mapUrl = $mapUrl
        . "&markers=color:green|label:P|"
            . $_GET['location']
            . "";            
    }
    
    $variables ['mapURL'] = $mapUrl;
    $variables ['title'] = "Hydranten";
    $variables ['subtitle'] = $engineDAO->getEngine($engine)->getName();
} else {
    $variables ['alertMessage'] = "Zug nicht festgelegt";
}
  
renderLayoutWithContentFile($variables);
