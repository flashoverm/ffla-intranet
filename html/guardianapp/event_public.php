<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["guardian"],
		'template' => "eventPublic_template.php",
	    'title' => "Öffentliche Wachen",
	    'secured' => false,
);
$variables = checkPermissions($variables);

if($config["settings"]["publicevents"]){
	$variables ['events'] =  $eventDAO->getPublicEvents();
} else {
	$variables ['alertMessage'] = "Öffentliche Wachen deaktiviert - <a href=\"" . $config["urls"]["intranet_home"] . "/login\" class=\"alert-link\">Zum Login</a>";
}


renderLayoutWithContentFile ($variables );
?>