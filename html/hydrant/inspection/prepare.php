<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";


// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "inspectionPrepare_template.php",
		'title' => "Hydrantenprüfung planen",
		'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER
);
$variables = checkPermissions($variables);

//get hydrants by engine with last checkup not set or older than 6 years
$hydrants = $hydrantDAO->getUncheckedHydrantsOfEngine($userController->getCurrentUser()->getEngine()->getUuid());


$mapUrl = $config["mapView"]["apiUrl"]
. "?key=" . $config["mapView"]["apiKey"]
. "&size=" . $config["mapView"]["widewidth"] . "x" . $config["mapView"]["height"]
. "&scale=" . $config["mapView"]["retina"]
. "&maptype=" . $config["mapView"]["maptype"]
. "&markers=color:red|label:H";

foreach ( $hydrants as $hydrant ) {
    $mapUrl = $mapUrl . "|" . $hydrant->getLat() . "," . $hydrant->getLng() . "";
}

$variables ['mapURL'] = $mapUrl;
$variables ['hydrants'] = $hydrants;

renderLayoutWithContentFile($variables);