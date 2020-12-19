<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

function check_password($user, $password) {
	if ($password == $user->getPassword() ) {
		return $user->getUuid();
	}
	if (password_verify ( $password, $user->getPassword() )) {
		return $user->getUuid();
	}
	return false;
}

if (userLoggedIn()) {
	header ( "Location: " . $config["urls"]["intranet_home"] . "/" ); // redirects
}

// Pass variables (as an array) to template
$variables = array (
		'title' => "Intranet",
		'subtitle' => "der Freiwilligen Feuerwehr der Stadt Landshut",
		'secured' => false
);

if(isset($_SESSION["ref"])){
	$variables ['infoMessage'] = "Bitte zuerst einloggen";
}

if (isset ( $_POST ['email'] ) && isset ( $_POST ['password'] )) {

    $email = strtolower(trim ( $_POST ['email'] ));
	$password = trim ( $_POST ['password'] );
	
	$loggedIn = false;
	$user = $userDAO->getUserByEmail($email);
	if ( $user != null && ! $user->getLocked() && ! $user->getDeleted() ) {
		$uuid = check_password ( $user, $password );
		if ($uuid) {
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserLogedIn, $uuid));
			
			$_SESSION ['intranet_userid'] = $uuid;
			
			$loggedIn = true;
			
			if(isset($_SESSION["ref"]) && $_SESSION["ref"] != ""){
				$ref = $_SESSION["ref"];
				unset($_SESSION["ref"]);
				header ( "Location: " . $config["urls"]["intranet_home"] . $ref ); // redirects	
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

renderLayoutWithContentFile ($config["apps"]["landing"], "login_template.php", $variables );

?>