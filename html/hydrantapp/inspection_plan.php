<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_inspection.php";

$hydrants = get_candidates_of_engine(get_engine_of_user($_SESSION ['intranet_userid']));


$mapUrl = $config["mapView"]["apiUrl"]
. "?key=" . $config["mapView"]["apiKey"]
. "&size=" . $config["mapView"]["widewidth"] . "x" . $config["mapView"]["height"]
. "&scale=" . $config["mapView"]["retina"]
. "&maptype=" . $config["mapView"]["maptype"]
. "&markers=color:red|label:H";

foreach ( $hydrants as $hydrant ) {
	$mapUrl = $mapUrl . "|" . $hydrant->lat . "," . $hydrant->lng . "";
}

$variables ['mapURL'] = $mapUrl;
$variables ['hydrants'] = $hydrants;

renderContentFile($config["apps"]["hydrant"], "inspectionPlan_template.php", $variables);

?>
