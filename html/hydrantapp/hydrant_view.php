<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "hydrantView_template.php",
	    'title' => "Hydrant",
	    'secured' => true
);
$variables = checkPermissions($variables);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        
    $id = trim($_GET['id']);
        
    $hydrant = $hydrantDAO->getHydrantByHy($id);
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
            . $hydrant->getLat()
            . ","
            . $hydrant->getLng()
            . "&markers=color:red|label:H|"
            . $hydrant->getLat()
            . ","
            . $hydrant->getLng()
            . "";
                    
        if(isset($_GET['location'])) {
            $mapUrl = $mapUrl
            . "&markers=color:green|label:P|"
            . $_GET['location']
            . "";
            
            $variables ['infoMessage'] = "Wird die eigene Position (Grün) nicht angezeigt, befindet sie sich außerhalb des Kartenausschnitt"; 
            $variables ['mapURL'] = $mapUrl;
            
        } else {
        	if( ! $hydrantController->isMapExisting($id)){
                         
                $imagePath = $config["paths"]["maps"];
                $imageFile = $id . ".png";
                
                if (!file_exists($config["paths"]["maps"])) {
                    mkdir($config["paths"]["maps"], 0777, true);
                }
                
                if(!is_writeable($imagePath)){
                    echo "Error saving file - Path " . $imagePath . " not existing or writable";
                }
                file_put_contents($imagePath . $imageFile, file_get_contents($mapUrl));
                $hydrant->setMap($imageFile);
                $hydrant = $hydrantDAO->save($hydrant);
                $variables ['hydrant'] = $hydrant;
            }

            $variables ['mapURL'] = $config["urls"]["hydrantapp_home"] . "/" . $hydrant->getHy() . "/map";
        }
       
    } else {
        $variables ['alertMessage'] = "Hydrant-ID existiert nicht";
    }
} else {
    $variables ['alertMessage'] = "Hydrant-ID nicht festgelegt";
}

renderLayoutWithContentFile($variables);

?>