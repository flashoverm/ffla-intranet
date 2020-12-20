<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Formulare",
    'secured' => true
);

$variables ['files'] = $fileDAO->getFiles();

renderLayoutWithContentFile($config["apps"]["files"], "forms_template.php", $variables);

?>
