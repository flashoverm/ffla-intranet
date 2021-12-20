<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

$privileges = $privilegeDAO->getPrivileges();

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["users"],
		'template' => "privilegeEdit_template.php",
	    'title' => "Rechte bearbeiten",
	    'secured' => true,
		'privilege' => Privilege::PORTALADMIN,	
		'privileges' => $privileges,
);
checkSitePermissions($variables);

if( isset( $_GET["user"] ) ) {
	
	if( isset ( $_POST["updateRights"] ) ) {
		
		$update = false;
		
		$user = $userDAO->getUserByUUID( $_GET["user"] );
		if(isset($_GET['engine'])){
			$engine = $engineDAO->getEngine($_GET['engine']);
			$update = true;
		} else if(isset($_POST['engine'])) {
			$engine = $engineDAO->getEngine($_POST['engine']);
		}
		
		$enginePrivileges = array();
		foreach($privileges as $privilege){
			
			$inputName = "priv_" . $privilege->getUuid();
			if(isset ( $_POST [ $inputName ] )){
				$enginePrivileges [] = $privilege;
			}
		}
		
		if( ! $update && $user->hasEngine($engine) ){
			
			$variables ['alertMessage'] = $engine->getName() . " bereits vorhanden";
	
		} else {
			
			$user->addAdditionalEngine($engine);
			$user->resetPrivilegeForEngine($engine, $enginePrivileges);
			$user = $userDAO->save($user);
			
			if($user){
				
				unset($_POST);
				
				if( isset( $_GET["engine"] ) ) {
					$variables ['successMessage'] = "Rechte für " . $engine->getName() . " aktualisiert";
				} else {
					$variables ['successMessage'] = $engine->getName() . " hinzugefügt";
				}
			} else {
				if( isset( $_GET["engine"] ) ) {
					$variables ['alertMessage'] = "Rechte für " . $engine->getName() . " konnten nicht aktualisiert werden";
				} else {
					$variables ['alertMessage'] = $engine->getName() . " konnte nicht hinzugefügt werden";
				}
			}
			
		}
	} else {
		$user = $userDAO->getUserByUUID( $_GET["user"] );
	}
	
	$variables['user'] = $user;
	
	if( isset( $_GET["engine"] ) ) {
		//Change existing engines/privileges
		
		$engine = $engineDAO->getEngine( $_GET["engine"] );
		$variables['engine'] = $engine;
		$variables['engines'] = $user->getAdditionalEngines();
	} else {
		//Add new additional engine with privileges
		
		$possibleEngines = array();
		foreach($engineDAO->getEngines() as $engine){
			
			if( ! $user->hasEngine($engine) ){
				$possibleEngines[] = $engine;
			}
		}

		$variables['engines'] = $possibleEngines;
		$variables['title'] = "Zusätzliche Einheit hinzufügen";
	}
} else {
	//Fehler: Kein Benutzer ausgewählt
}

renderLayoutWithContentFile ($variables );
