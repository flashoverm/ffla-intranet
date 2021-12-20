<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["files"],
		'template' => "forms_template.php",
	    'title' => "Formulare",
	    'secured' => true
);
checkSitePermissions($variables);

$variables ['files'] = $fileDAO->getFiles();

renderLayoutWithContentFile($variables);

?>
