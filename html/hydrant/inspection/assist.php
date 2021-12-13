<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "inspectionAssist/inspectionAssist_template.php",
	    'title' => "Prüfbericht erstellen",
	    'secured' => true,
	    'privilege' => Privilege::ENGINEHYDRANTMANANGER
);
$variables = checkSitePermissions($variables);

$variables['criteria'] = InspectedHydrant::HYDRANTCRITERIA;

renderLayoutWithContentFile($variables);
