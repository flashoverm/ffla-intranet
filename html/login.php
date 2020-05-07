<?php
require_once realpath(dirname(__FILE__) . "/../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";


if (isset ( $_SESSION ['intranet_userid'] )) {
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

if (isset ( $_POST ['username'] ) && isset ( $_POST ['password'] )) {

    $username = strtolower(trim ( $_POST ['username'] ));
	$password = trim ( $_POST ['password'] );

	if (login_enabled ( $email )) {
		$uuid = check_password ( $username, $password );
		if ($uuid) {
			$_SESSION ['intranet_userid'] = $uuid;
			$_SESSION ['intranet_username'] = $username;
			
			if(isset($_SESSION["ref"]) && $_SESSION["ref"] != ""){
				$ref = $_SESSION["ref"];
				unset($_SESSION["ref"]);
				header ( "Location: " . $ref ); // redirects	
			} else {
				header ( "Location: " . $config["urls"]["intranet_home"] . "/" ); // redirects
			}
		}
	}
	$variables ['alertMessage'] = "Zugangsdaten ungültig";
}

renderLayoutWithContentFile ($config["apps"]["landing"], "login_template.php", $variables );

?>