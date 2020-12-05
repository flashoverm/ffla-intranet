<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_hydrant.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Straße nicht festgelegt",
    'secured' => true
);

if (isset($_GET['street'])) {

	$street = trim($_GET['street']);
    
    $hydrants = get_hydrants_of_street($street);
    
    $variables ['hydrants'] = $hydrants;
    
    $mapUrl = $config["mapView"]["apiUrl"]
    . "?key=" . $config["mapView"]["apiKey"]
    . "&size=" . $config["mapView"]["widewidth"] . "x" . $config["mapView"]["height"]
    . "&scale=" . $config["mapView"]["retina"]
    . "&maptype=" . $config["mapView"]["maptype"]
    . "&markers=color:red|label:H";
    
    foreach ( $hydrants as $hydrant ) {
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
    $variables ['subtitle'] = $street;
} else {
    $variables ['alertMessage'] = "Straße nicht festgelegt";
}
  
renderLayoutWithContentFile($config["apps"]["hydrant"], "hydrantStreetView_template.php", $variables);
