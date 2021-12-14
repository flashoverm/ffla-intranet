<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

if( SessionUtil::userLoggedIn() ){
	header ( "Location: " . $config["urls"]["intranet_home"] . "/" ); // redirects
}

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["users"],
		'template' => "resetPassword_template.php",
	    'title' => "Passwort zurücksetzen",
		'secured' => false,
);
$variables = checkSitePermissions($variables);

$token = null;
if( ! isset($_GET['token']) ) {
	
	$variables['showFormular'] = false;
	$variables['alertMessage'] = "Es wurde kein Token übergeben";
	
} else {
	
	$token = $tokenDAO->getTokenByToken($_GET['token']);
	if($token){
		if( ! $token->isValid()){
			$variables['showFormular'] = false;
			$variables['alertMessage'] = "Link ist abgelaufen - Bitte erneut anfordern";
		}
	} else {
		$variables['showFormular'] = false;
		$variables['alertMessage'] = "Link ungültig";
	}
}

if ( isset($_GET['token']) && isset($_POST['password']) && isset($_POST['password2']) ) {

    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    $error = false;
    if ($password != $password2) {
        $variables['alertMessage'] = "Die Passwörter müssen übereinstimmen";
        $error = true;
    }

    if (! $error) {
    	if($userController->resetPasswordWithToken($token, $password)){
    		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserResetPasswordLink, $token->getUser()->getUuid()));
    		$variables['successMessage'] = "Password erfolgreich zurückgesetzt";
    	} else {
    		$variables['alertMessage'] = "Passwort konnte nicht zurückgesetzt werden!";
    	}
    }
}

renderLayoutWithContentFile($variables);

?>