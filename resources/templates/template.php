<?php
require_once LIBRARY_PATH . '/util.php';
require_once LIBRARY_PATH . "/ui_util.php";
require_once LIBRARY_PATH . "/ui_render.php";

function checkFunctionPermissions(array $privileges, $variables = array()){
	$newVariables = $variables;
	$newVariables['privilege'] = $privileges;
	$newVariables['isFunction'] = true;
	checkSitePermissions($newVariables);
}

function checkFunctionPermission(string $privilege, $variables = array()){
	$newVariables = $variables;
	$newVariables['privilege'] = $privilege;
	$newVariables['isFunction'] = true;
	checkSitePermissions($newVariables);
}

function checkSitePermissions($variables = array()){
	global $currentUser;
	
	$variables['currentUser'] = $currentUser;
	
	$localhostRequest = localhostRequest();
	
	if($localhostRequest){
		//skip privilege check on localhost-request
		return $variables;
	}
	
	//check if logged in user is required
	if (isset($variables['secured']) && $variables['secured'] && (! isset($currentUser) || ! $currentUser) ) {
		goToLogin();
	}
	
	// check privileges
	if(isset($variables['privilege'])){
		
		if(is_array($variables['privilege'])){
			//Multible privileges possible
			if(userHasOnePrivilege($variables['privilege'], true)){
				//User has one of the possible required privileges -> OK
				return $variables;
			}
		} else {
			if(userHasPrivilege($variables['privilege'], true)){
				//User has required privilege -> OK
				return $variables;
			}
		}
		
	} else {
		//case: no privilege required -> OK
		return $variables;
	}
	
	//required privilege missing -> not OK
	if(isset($variables['isFunction']) && $variables['isFunction']){
		$variables['alertMessage'] = "Sie haben keine Berechtigung diese Aktion auszuführen";
	} else {
		$variables['alertMessage'] = "Sie haben keine Berechtigung diese Seite anzuzeigen";
	}
	$variables['showFormular'] = false;
	if( ! isset($variables['title'])){
		$variables['title'] = "Keine Berechtigung";
	}
	renderLayoutWithContentFile($variables);
	//stop processing because of missing privilege
	exit();
	
}

function renderPrintContentFile($variables = array()){
	global $config, $userController, $userDAO, $engineDAO, $guardianUserController, 
	$eventTypeDAO, $logbookDAO, $mailLogDAO, $staffPositionDAO, $hydrantDAO, $eventController;

    if (count ( $variables ) > 0) {
        foreach ( $variables as $key => $value ) {
            if (strlen ( $key ) > 0) {
                ${$key} = $value;
            }
        }
    }
    
    require_once (TEMPLATES_PATH . "/header_print.php");
    
    if(isset($alertMessage)){
    	showAlert($alertMessage);
    }
    
    if(isset($successMessage)){
    	showSuccess($successMessage);
    }
    
    if(isset($infoMessage)){
    	showInfo($infoMessage);
    }
    
    if(!isset($showFormular) || $showFormular){
    	$contentFileFullPath = TEMPLATES_PATH . "/" . $app .  "/pages/" . $template;
    	if (file_exists ( $contentFileFullPath )) {
    		require_once ($contentFileFullPath);
    	} else {
    		echo "Requested template  " . $contentFileFullPath . " not existing";
    	}
    }
        
    echo "</body>
    <script>
    window.onload=hideLoader;
    </script>
    </html>";
}

function renderLayoutWithContentFile($variables = array()) {
	global $config, $userController, $userDAO, $engineDAO, $guardianUserController, 
	$eventTypeDAO, $logbookDAO, $mailLogDAO, $staffPositionDAO, $hydrantDAO, $eventController;

	if (count ( $variables ) > 0) {
		foreach ( $variables as $key => $value ) {
			if (strlen ( $key ) > 0) {
				${$key} = $value;
			}
		}
	}
	
	require_once (TEMPLATES_PATH . "/header.php");
	
	//Footer is always added to the bottom so it can be placed before the cotent-container
	require_once (TEMPLATES_PATH . "/footer.php");
	
	echo "<div class=\"container content-container\" id=\"container\">\n";
	echo "\t<div id=\"content\" class=\"content\" style=\"display:none;\">\n";

	if(isset($alertMessage)){
		showAlert($alertMessage);
	}
	
	if(isset($successMessage)){
		showSuccess($successMessage);
	}
	
	if(isset($infoMessage)){
		showInfo($infoMessage);
	}

	if(!isset($showFormular) || $showFormular){
		$contentFileFullPath = TEMPLATES_PATH . "/" . $app .  "/pages/" . $template;
		if (file_exists ( $contentFileFullPath )) {
			require_once ($contentFileFullPath);
		} else {
			echo "Requested template  " . $contentFileFullPath . " not existing";
		}
	}

	// close content div
	echo "\t</div>\n";

	// close container div
	echo "</div>\n";

}

?>