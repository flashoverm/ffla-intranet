<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "hydrantStreetView_template.php",
	    'title' => "Straße nicht festgelegt",
	    'secured' => true
);
checkSitePermissions($variables);

if (isset($_GET['street'])) {

	$street = trim($_GET['street']);
    
	$hydrants = $hydrantDAO->getHydrantsOfStreet($street, $_GET);
    
    $variables ['hydrants'] = $hydrants;
    
    $mapUrl = $config["mapView"]["apiUrl"]
    . "?key=" . $config["mapView"]["apiKey"]
    . "&size=" . $config["mapView"]["widewidth"] . "x" . $config["mapView"]["height"]
    . "&scale=" . $config["mapView"]["retina"]
    . "&maptype=" . $config["mapView"]["maptype"]
    . "&markers=color:red|label:H";
    
    foreach ( $hydrants as $hydrant ) {
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
    $variables ['subtitle'] = $street;
} else {
    $variables ['alertMessage'] = "Straße nicht festgelegt";
}
  
renderLayoutWithContentFile($variables);
