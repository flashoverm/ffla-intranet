<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["landing"],
		'template' => "setView_template.php",
	    'title' => "Ansicht festlegen",
	    'secured' => true,	
);
checkSitePermissions($variables);

$redirect = false;

if(isset($_POST["ref"]) && ! isset($_SESSION["ref"])){
	$_SESSION["ref"] = $_POST["ref"];
}

if( ! count($currentUser->getAdditionalEngines()) > 0 ){
	$_SESSION["setEngine"] = $currentUser->getEngine()->getUuid();
	$redirect = true;	
}

if( isset( $_GET["engine"] ) ){
	$engine = $engineDAO->getEngine($_GET["engine"]);
	if($currentUser->hasEngine($engine)){
		$_SESSION["setEngine"] = $engine->getUuid();
	} else {
	    $_SESSION["setEngine"] = $currentUser->getEngine()->getUuid();
	}
	$redirect = true;
}

if($redirect){
	if(isset($_SESSION["ref"]) && $_SESSION["ref"] != ""){
		$ref = $_SESSION["ref"];
		unset($_SESSION["ref"]);
		header ( "Location: " . $config["urls"]["intranet_home"] . $ref ); // redirects
	} else {
		header ( "Location: " . $config["urls"]["intranet_home"] . "/" ); // redirects
	}
}

$variables['additionalEngines'] = $currentUser->getAdditionalEngines();

renderLayoutWithContentFile($variables);
