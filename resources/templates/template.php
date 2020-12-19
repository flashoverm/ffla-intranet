<?php
require_once LIBRARY_PATH . '/util.php';
require_once LIBRARY_PATH . "/ui_util.php";

session_start ();

function renderPrintContentFile($app, $contentFile, $variables = array(), $noHeader = false){
	global $config, $userController, $userDAO, $engineDAO, $guardianUserController, 
		$logbookDAO, $mailLogDAO;
    
    $contentFileFullPath = TEMPLATES_PATH . "/" . $app .  "/pages/" . $contentFile;
    
    // making sure passed in variables are in scope of the template
    // each key in the $variables array will become a variable
    if (count ( $variables ) > 0) {
        foreach ( $variables as $key => $value ) {
            if (strlen ( $key ) > 0) {
                ${$key} = $value;
            }
        }
    }
    
   
    $currentUser = $userController->getCurrentUser();
    $localhostRequest = localhostRequest();
    
    if ( isset($secured) && $secured && ! $currentUser && ! $localhostRequest) {
    	showAlert("Sie haben keine Berechtigung diese Seite anzuzeigen");
    	return;
    }
    
        
    require_once (TEMPLATES_PATH . "/header_print.php");
    
    if( isset($privilege) && ! $currentUser->hasPrivilegeByName($privilege) && ! $localhostRequest ){
    	showAlert("Sie haben keine Berechtigung diese Seite anzuzeigen");
    	$showFormular = false;
    }
    
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



function renderLayoutWithContentFile($app, $contentFile, $variables = array()) {
	global $config, $userController, $userDAO, $engineDAO, $guardianUserController, 
		$logbookDAO, $mailLogDAO;

	$contentFileFullPath = TEMPLATES_PATH . "/" . $app .  "/pages/" . $contentFile;

	// making sure passed in variables are in scope of the template
	// each key in the $variables array will become a variable
	if (count ( $variables ) > 0) {
		foreach ( $variables as $key => $value ) {
			if (strlen ( $key ) > 0) {
				${$key} = $value;
			}
		}
	}

	$currentUser = $userController->getCurrentUser();
	
	if ($secured && ! $currentUser) {
        goToLogin();		
	}
	
	$currentUser = $userController->getCurrentUser();
	
	require_once (TEMPLATES_PATH . "/header.php");

	echo "<div class=\"container\" id=\"container\">\n" . "\t<div id=\"content\">\n";

	if(isset($privilege) && ! $currentUser->hasPrivilegeByName($privilege)){
		showAlert("Sie haben keine Berechtigung diese Seite anzuzeigen");
		$showFormular = false;
	}

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