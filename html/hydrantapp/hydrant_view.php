<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_hydrant.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Hydrant",
    'secured' => true
);

if (isset($_GET['id'])) {
        
    $id = trim($_GET['id']);
        
    $hydrant = get_hydrant($id);
    if($hydrant){
        $variables ['hydrant'] = $hydrant;
        $variables ['title'] = "Hydrant " . $id;
        
        $mapUrl = $config["mapView"]["apiUrl"]
            . "?key=" . $config["mapView"]["apiKey"]
            . "&size=" . $config["mapView"]["width"] . "x" . $config["mapView"]["height"]
            . "&scale=" . $config["mapView"]["retina"]
            . "&zoom=" . $config["mapView"]["zoom"]
            . "&maptype=" . $config["mapView"]["maptype"]
            . "&center="
            . $hydrant->lat
            . ","
            . $hydrant->lng
            . "&markers=color:red|label:H|"
            . $hydrant->lat
            . ","
            . $hydrant->lng
            . "";
                    
        if(isset($_GET['location'])) {
            $mapUrl = $mapUrl
            . "&markers=color:green|label:P|"
            . $_GET['location']
            . "";
            
            $variables ['infoMessage'] = "Wird die eigene Position (Grün) nicht angezeigt, befindet sie sich außerhalb des Kartenausschnitt"; 
            $variables ['mapURL'] = $mapUrl;
            
        } else {
        	if(!is_map_existing($id)){
                         
                $imagePath = $config["paths"]["maps"];
                $imageFile = $id . ".png";
                
                if (!file_exists($config["paths"]["maps"])) {
                    mkdir($config["paths"]["maps"], 0777, true);
                }
                
                if(!is_writeable($imagePath)){
                    echo "Error saving file - Path " . $imagePath . " not existing or writable";
                }
                file_put_contents($imagePath . $imageFile, file_get_contents($mapUrl));
                save_map($id, $imageFile);
                
                $hydrant = get_hydrant($id);
                $variables ['hydrant'] = $hydrant;
            }

            $variables ['mapURL'] = $config["urls"]["hydrantapp_home"] . "/" . $hydrant->hy . "/map";
        }
       
    } else {
        $variables ['alertMessage'] = "Hydrant-ID existiert nicht";
    }
} else {
    $variables ['alertMessage'] = "Hydrant-ID nicht festgelegt";
}

renderLayoutWithContentFile($config["apps"]["hydrant"], "hydrantView_template.php", $variables);

?>