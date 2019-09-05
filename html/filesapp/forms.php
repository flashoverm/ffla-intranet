<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_files.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Formulare",
    'secured' => true
);

$variables ['files'] = get_files();

renderLayoutWithContentFile($config["apps"]["files"], "forms_template.php", $variables);

?>
