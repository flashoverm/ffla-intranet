<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["pager"],
		'template' => "pagerMap_template.php",
	    'title' => "Alarmierungsauswertung",
	    'secured' => true
);
checkSitePermissions($variables);

$_GET[ResultSet::SHOWALL_PARAM] = true;

$pagerAlerts = new ResultSet();
$pagerAlerts->setData(array());

$pagerAlerts = $pagerAlertDAO->getPagerAlerts($_GET);
//$pagerAlertsUnalerted = $pagerAlertDAO->getUnalertedPagerAlerts($_GET);

$variables ['pagerAlerts'] = $pagerAlerts;


$mapUrl = $config["mapView"]["apiUrl"]
. "?key=" . $config["mapView"]["apiKey"]
. "&size=" . $config["mapView"]["widewidth"] . "x" . $config["mapView"]["height"]
. "&scale=" . $config["mapView"]["retina"]
. "&maptype=" . $config["mapView"]["maptype"]
. "&markers=color:red|label:H";

foreach ( $pagerAlerts as $alert ) {
    $mapUrl = $mapUrl . "|" . $alert->getLat() . "," . $alert->getLng() . "";
}

$variables ['mapURL'] = $mapUrl;

renderLayoutWithContentFile($variables);
