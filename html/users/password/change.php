<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["users"],
		'template' => "changePassword_template.php",
	    'title' => "Passwort ändern",
	    'secured' => true,
		'privilege' => Privilege::EDITUSER,	
);
$variables = checkSitePermissions($variables);

if (isset($_POST['password_old']) && isset($_POST['password']) && isset($_POST['password2']) && SessionUtil::userLoggedIn()) {

	$uuid = SessionUtil::getCurrentUserUUID();
    $password_old = trim($_POST['password_old']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    $error = false;
    if ($password != $password2) {
        $variables['alertMessage'] = "Die Passwörter müssen übereinstimmen";
        $error = true;
    }

    if (! $error) {
    	if($userController->changePassword($uuid, $password_old, $password)){
    		$variables['successMessage'] = "Password erfolgreich geändert";
    	} else {
    		$variables['alertMessage'] = "Passwort konnte nicht geändert werden!";
    	}
    }
}

renderLayoutWithContentFile($variables);

?>