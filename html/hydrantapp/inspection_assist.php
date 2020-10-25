<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_hydrant.php";
require_once LIBRARY_PATH . "/db_inspection.php";

require_once LIBRARY_PATH . "/class/constants/HydrantCriteria.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Prüfbericht erstellen",
    'secured' => true,
    'privilege' => ENGINEHYDRANTMANANGER
);

$variables['criteria'] = $hydrant_criteria;

renderLayoutWithContentFile($config["apps"]["hydrant"], "inspectionAssist/inspectionAssist_template.php", $variables);
