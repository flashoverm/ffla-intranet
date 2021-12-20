<?php
require_once LIBRARY_PATH . "/ui_util.php";
require_once LIBRARY_PATH . "/ui_render.php";

function checkPermissions(array $rules, $variables = array()){
	/*
	 * rules: list (array) of rules (OR)
	 * rule:  combination of user, engine or single privilege (AND)
	 */
	
	if(SessionUtil::localhostRequest()){
		//skip privilege check on localhost-request
		return;
	}
	
	if(isset($rules[0]) && is_array($rules[0])){
		foreach($rules as $rule){
			if(PrivilegeUtil::checkRule($rule)){
				return;
			}
		}
	} else {
		if(PrivilegeUtil::checkRule($rules)){
			return;
		}
	}
	
	renderErrorPage(403, $variables);
}

function checkSitePermissions($variables){
	global $currentUser;
	
	if(SessionUtil::localhostRequest()){
		//skip privilege check on localhost-request
		return;
	}
	
	//check if logged in user is required
	if (isset($variables['secured']) && $variables['secured'] && (! isset($currentUser) || ! $currentUser) ) {
		SessionUtil::goToLogin();
	}
	
	if(isset($variables['privilege'])){
		if( ! PrivilegeUtil::checkPrivileges($variables['privilege'])){
			renderErrorPage(403, $variables);
		}
	}
}

function renderErrorPage($code, $variables = array()){
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$variables['alertMessage'] = "Sie haben keine Berechtigung diese Aktion durchzuführen";
	} else {
		$variables['alertMessage'] = "Sie haben keine Berechtigung diese Seite anzuzeigen";
	}
	

	$variables['showFormular'] = false;
	if( ! isset($variables['title'])){
		$variables['title'] = "Keine Berechtigung";
	}
	http_response_code($code);
	renderLayoutWithContentFile($variables);
	//stop processing because of missing privilege/wrong user
	exit();
}

function renderPrintContentFile($variables = array()){
	global $config, $currentUser, $userController, $userDAO, $engineDAO, $guardianUserController, 
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
	global $config, $currentUser, $userController, $userDAO, $engineDAO, $guardianUserController, 
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