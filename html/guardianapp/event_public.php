<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
    'title' => "Öffentliche Wachen",
    'secured' => false,
);

if($config["settings"]["publicevents"]){
	$variables ['events'] =  $eventDAO->getPublicEvents();
} else {
	$variables ['alertMessage'] = "Öffentliche Wachen deaktiviert - <a href=\"" . $config["urls"]["intranet_home"] . "/login\" class=\"alert-link\">Zum Login</a>";
}


renderLayoutWithContentFile ($config["apps"]["guardian"], "eventPublic_template.php", $variables );
?>