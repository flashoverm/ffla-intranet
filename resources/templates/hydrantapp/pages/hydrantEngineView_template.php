<?php
if (! count ( $hydrants->getData() )) {
    showInfo ( "Keine Hydranten für diesen Zug gefunden" );
} else {
    
    $options = array(
    );
    
    render(TEMPLATES_PATH . "/hydrantapp/elements/hydrant_table.php", $hydrants, $options);
?>
<div class="custom-control custom-checkbox">
  <input type="checkbox" class="custom-control-input" id="toogleMaps" checked>
  <label class="custom-control-label" for="toogleMaps">Verwende Google Maps</label>
</div>
<br>

<?php 
    if($mapURL != null){
        echo "<img id='map' class='rounded mx-auto' width='" . $config["mapView"]["widewidth"] . "' src='" . $mapURL . "' style='display: none;'>";
    }
    createHydrantGoogleMap($hydrants->getData(), true, true, $config["mapView"]["height"] . "px", $config["mapView"]["widewidth"] . "px");
}
?>


