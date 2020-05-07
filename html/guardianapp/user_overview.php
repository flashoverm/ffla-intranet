<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'title' => "Übersicht Benutzer",
		'secured' => true,
		'privilege' => EVENTADMIN
);

if (isset ( $_POST ['disable'] )) {
	$user_uuid = trim ( $_POST ['disable'] );
	if(hide_user ( $user_uuid )) {
		$variables ['successMessage'] = "Für Wachen gesperrt";	
	} else {
		$variables ['alertMessage'] = "Sperren fehlgeschlagen";
	}
}
if (isset ( $_POST ['enable'] )) {
	$user_uuid = trim ( $_POST ['enable'] );
	if(show_user ( $user_uuid )){
		$variables ['successMessage'] = "Für Wachen freigegeben";
	} else {
		$variables ['alertMessage'] = "Freigabe fehlgeschlagen";
	}
}

$user = get_all_user ();
$variables ['user'] = $user;

renderLayoutWithContentFile ($config["apps"]["guardian"], "userOverview_template.php", $variables );

?>