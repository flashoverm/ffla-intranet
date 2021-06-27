<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

if(! userLoggedIn()){
    http_response_code(401);
} else {
    if (isset($_GET['fid']) && isset($_GET['fieldid'])) {
        
        $hydrant = $hydrantDAO->getHydrantByFid($_GET['fid']);
        
        if($hydrant){
        	header('Content-Type: text/plain');
        	
        	if( $hydrant->getCheckByFF() && $hydrant->getEngine()->getUuid() == $userController->getCurrentUser()->getEngine()->getUuid() ){
	            echo "{ fieldid:" . $_GET['fieldid'] . ", hy:" . $hydrant->getHy() . ", status:'ok'}";
        	} else {
        		http_response_code(403);
        	}
        } else {
        	http_response_code(404);
        }
    } else {
        http_response_code(400);
    }
}

?>