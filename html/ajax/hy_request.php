<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once LIBRARY_PATH . "/db_hydrant.php";
require_once LIBRARY_PATH . "/db_user.php";

session_start ();

$user = isset ( $_SESSION ['intranet_userid'] );

if(!$user){
    http_response_code(401);
} else {
    if (isset($_GET['fid']) && isset($_GET['fieldid'])) {
        
        $hydrant = get_hydrant_by_fid($_GET['fid']);
        
        if($hydrant){
        	header('Content-Type: text/plain');
        	
        	if( $hydrant->checkbyff && $hydrant->engine == get_engine_of_user($_SESSION ['intranet_userid']) ){
	            echo "{ fieldid:" . $_GET['fieldid'] . ", hy:" . $hydrant->hy . ", status:'ok'}";
        	} else {
        		echo "{ fieldid:" . $_GET['fieldid'] . ", status:'forbidden'}";
        	}
        } else {
        	echo "{ fieldid:" . $_GET['fieldid'] . ", status:'notfound'}";
        }
    } else {
        http_response_code(400);
    }
}

?>