<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_confirmation.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Arbeitgeberbestätigung",
		'secured' => true,
		'orientation' => 'portrait'
);

if (isset($_GET['id'])) {
	
	$confirmation = get_confirmation($_GET['id']);
	$user = get_user($confirmation->user);
	
	$variables['confirmation'] = $confirmation;
	$variables['user'] = $user;
}

renderPrintContentFile($config["apps"]["employer"], "confirmationPrint_template.php", $variables, true);