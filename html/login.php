<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

if (SessionUtil::userLoggedIn()) {
	header ( "Location: " . $config["urls"]["intranet_home"] . "/" ); // redirects
}

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["landing"],
		'template' => "login_template.php",
		'title' => "Intranet",
		'subtitle' => "der Freiwilligen Feuerwehr der Stadt Landshut",
		'secured' => false
);
$variables = checkSitePermissions($variables);

if(isset($_SESSION["ref"])){
	$variables ['infoMessage'] = "Bitte zuerst einloggen";
}

if (isset ( $_POST ['email'] ) && isset ( $_POST ['password'] )) {

    $email = strtolower(trim ( $_POST ['email'] ));
	$password = trim ( $_POST ['password'] );
	
	$loggedIn = false;
	$user = $userDAO->getUserByEmail($email);
	if ( $user != null && ! $user->getLocked() && ! $user->getDeleted() ) {
		$uuid = $userController->checkPassword( $user, $password );
		if ($uuid) {
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserLogedIn, $uuid));
			$user->setLastLogin(date('Y-m-d H:i:s'));
			$userDAO->save($user);
			
			SessionUtil::setCurrentUserUUID($uuid);
			
			$loggedIn = true;
			
			if(isset($_SESSION["ref"]) && $_SESSION["ref"] != ""){
				$ref = $_SESSION["ref"];
				unset($_SESSION["ref"]);
				header ( "Location: " . $config["urls"]["intranet_home"] . $ref ); // redirects	
				
			//} else if(count($userController->getCurrentUser()->getAdditionalEngines()) > 0) {
			//	header ( "Location: " . $config["urls"]["intranet_home"] . "/setView" ); // redirects	
			
			} else {
				header ( "Location: " . $config["urls"]["intranet_home"] . "/" ); // redirects
			}
		}
	}
	if( ! $loggedIn){
		$variables ['alertMessage'] = "Zugangsdaten ungültig";
		$user = $userDAO->getUserByEmail($email);
		if($user){
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserLoginFailed, $user->getUuid()));
		}
	}
}

renderLayoutWithContentFile ($variables );

?>