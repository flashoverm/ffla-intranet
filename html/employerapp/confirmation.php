<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Arbeitgeberbestätigung",
		'secured' => true,
		'orientation' => 'landscape'
);

if (isset($_GET['id'])) {
	
	$confirmation = get_confirmation($_GET['id']);
	
	$variables['confirmation'] = $confirmation;
	
}

renderPrintContentFile($config["apps"]["employer"], "confirmations/confirmation_template.php", $variables);