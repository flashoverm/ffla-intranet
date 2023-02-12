<?php 

if($mapURL != null){
    echo "<img id='map' class='rounded mx-auto' width='" . $config["mapView"]["widewidth"] . "' src='" . $mapURL . "' style='display: none;'>";
}
createPagerAlertMap($pagerAlerts->getData(), true, $config["mapView"]["height"] . "px", $config["mapView"]["widewidth"] . "px");
?>