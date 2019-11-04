<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_hydrant.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Hydrantenabfrage",
    'secured' => true
);

$variables ['engines'] = get_engines();

if (isset($_POST['hy'])) {
    
    $id = trim($_POST['hy']);
        
    if(is_hydrant_existing($id)){
        if(isset($_POST['location']) && $_POST['location'] != ""){
            header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/" . $id . "?location=" . $_POST['location']); // redirects
        } else {
        	header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/" . $id); // redirects
        }
    } else {
        $variables ['alertMessage'] = "Hydrant-ID existiert nicht";
    }
} else if(isset($_POST['street'])){
		
    $street = trim($_POST['street']);
    
    if(isset($_POST['locationStreet']) && $_POST['locationStreet'] != ""){
        header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/street/" . $street . "?location=" . $_POST['locationStreet']); // redirects
    } else {
		header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/street/" . $street); // redirects
    }  
    
} else if(isset($_POST['engine'])){
    
    $engine = trim($_POST['engine']);
    
    if(isset($_POST['locationEngine']) && $_POST['locationEngine'] != ""){
        header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/engine/" . $engine . "?location=" . $_POST['locationEngine']); // redirects
    } else {
        header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/engine/" . $engine); // redirects
    }
    
}

renderLayoutWithContentFile($config["apps"]["hydrant"], "hydrantRequest_template.php", $variables);

?>