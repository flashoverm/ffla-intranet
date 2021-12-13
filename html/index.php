<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["landing"],
		'template' => "index_template.php",
		'title' => 'Intranet',
		'subtitle' => 'der Freiwilligen Feuerwehr der Stadt Landshut',
		'secured' => false,
);
$variables = checkSitePermissions($variables);

renderLayoutWithContentFile ($variables);
