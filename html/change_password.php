<?php
require_once realpath(dirname(__FILE__) . "/../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Passwort ändern",
    'secured' => true
);

if (isset($_POST['password_old']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_SESSION['guardian_userid'])) {

    $uuid = $_SESSION['guardian_userid'];
    $password_old = trim($_POST['password_old']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    $error = false;
    if ($password != $password2) {
        $variables['alertMessage'] = "Die Passwörter müssen übereinstimmen";
        $error = true;
    }

    if (! $error) {
    	if(change_password($uuid, $password_old, $password)){
    		$variables['successMessage'] = "Password erfolgreich geändert";
    	} else {
    		$variables['alertMessage'] = "Passwort konnte nicht geändert werden!";
    	}
    }
}

renderLayoutWithContentFile($config["apps"]["landing"], "changePassword_template.php", $variables);

?>