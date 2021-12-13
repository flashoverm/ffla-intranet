<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["users"],
		'template' => "forgotPassword_template.php",
		'title' => "Passwort vergessen",
		'secured' => false,
);
$variables = checkSitePermissions($variables);

if( userLoggedIn() ){
	header ( "Location: " . $config["urls"]["intranet_home"] . "/" ); // redirects
}

$variables['infoMessage'] = "Ein Link, um das Passwort zurückzusetzen wird an die eingegebene E-Mail-Adresse gesendet<br>Aus Sicherheitsgründen wir nicht angezeigt, wenn die Adresse bei einem Benutzer hinterlegt ist";

if (isset($_POST['email']) ) {
	
	$email = trim($_POST['email']);
	
	$token = $userController->forgotPassword($email);
	if($token){
		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserRequestedPasswordLink, $token->getUser()->getUuid()));
		mail_forgot_password($email, $token->getToken());
	}
	
	//User is not informed if the email is not existing to prevent attacks
	
	$variables['successMessage'] = "Link wurde an die E-Mail-Adresse gesendet";

}
	
renderLayoutWithContentFile ($variables );
