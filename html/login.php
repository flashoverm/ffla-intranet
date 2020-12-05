<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";

function check_password($user, $password) {
	if ($password == $user->password ) {
		return $user->uuid;
	}
	if (password_verify ( $password, $user->password )) {
		return $user->uuid;
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
	$user = get_user_by_email($email);
	if ( $user != null && ! $user->locked && ! $user->deleted ) {
		$uuid = check_password ( $user, $password );
		if ($uuid) {
			insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::UserLogedIn, $uuid));
			
			$_SESSION ['intranet_userid'] = $uuid;
			$_SESSION ['intranet_email'] = $email;
			
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
		$user = get_user_by_email($email);
		if($user){
			insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::UserLoginFailed, $user->uuid));
		}
	}

}

renderLayoutWithContentFile ($config["apps"]["landing"], "login_template.php", $variables );

?>