<?php 
if (isset($hydrant)){    
    if($mapURL != null){
        echo "<img class='rounded mx-auto d-block' width='" . $config["mapView"]["width"] . "' src='" . $mapURL . "'>";
    }
}
?>
