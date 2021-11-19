<?php
require_once LIBRARY_PATH . '/util.php';
require_once LIBRARY_PATH . "/ui_util.php";
require_once LIBRARY_PATH . "/ui_render.php";

function checkPermissions($variables = array()){
	global $currentUser;
	
	$variables['currentUser'] = $currentUser;
	
	$localhostRequest = localhostRequest();
	
	if($localhostRequest){
		//skip privilege check on localhost-request
		return $variables;
	}
	
	//check if logged in user is required
	if ($variables['secured'] && (! isset($currentUser) || ! $currentUser) ) {
		goToLogin();
	}
	
	// check privileges
	if(isset($variables['privilege'])){
		
		if(isset($currentUser) && $currentUser){
			
			if( $currentUser->hasPrivilegeByName($variables['privilege']) ){
				//User has required privilege -> OK
				return $variables;
			}
		}
	} else {
		//case: no privilege required -> OK
		return $variables;
	}
	
	//required privilege missing -> not OK
	$variables['alertMessage'] = "Sie haben keine Berechtigung diese Seite anzuzeigen";
	$variables['showFormular'] = false;
	
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
    
    $contentFileFullPath = TEMPLATES_PATH . "/" . $app .  "/pages/" . $template;
    
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
    
    if (file_exists ( $contentFileFullPath )) {
        if(!isset($showFormular) || $showFormular){
            require_once ($contentFileFullPath);
        }
    } else {
    	echo "Requested template  " . $contentFileFullPath . " not existing";
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
	
	$contentFileFullPath = TEMPLATES_PATH . "/" . $app .  "/pages/" . $template;
	
	require_once (TEMPLATES_PATH . "/header.php");

	echo "<div class=\"container\" id=\"container\">\n" . "\t<div id=\"content\">\n";

	if(isset($alertMessage)){
		showAlert($alertMessage);
	}
	
	if(isset($successMessage)){
		showSuccess($successMessage);
	}
	
	if(isset($infoMessage)){
		showInfo($infoMessage);
	}

	if (file_exists ( $contentFileFullPath )) {
		if(!isset($showFormular) || $showFormular){
			require_once ($contentFileFullPath);
		}
	} else {
		echo "Requested template  " . $contentFileFullPath . " not existing";
	}

	// close content div
	echo "\t</div>\n";

	// close container div
	echo "</div>\n";

	require_once (TEMPLATES_PATH . "/footer.php");
}

?>