<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Prüfbericht erstellen",
    'secured' => true,
    'privilege' => Privilege::ENGINEHYDRANTMANANGER
);

$variables['criteria'] = InspectedHydrant::HYDRANTCRITERIA;

renderLayoutWithContentFile($config["apps"]["hydrant"], "inspectionAssist/inspectionAssist_template.php", $variables);
