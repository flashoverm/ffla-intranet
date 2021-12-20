<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["hydrant"],
		'template' => "manual_template.php",
	    'title' => 'Anleitung',
	    'secured' => false,
);
checkSitePermissions($variables);

renderLayoutWithContentFile ($variables );
