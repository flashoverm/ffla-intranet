<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once LIBRARY_PATH . "/db_hydrant.php";

session_start ();

$user = isset ( $_SESSION ['intranet_userid'] );

if(!$user){
    http_response_code(401);
} else {
    if (isset($_GET['fid']) && isset($_GET['fieldid'])) {
        
        $hydrant = get_hydrant_with_fid($_GET['fid']);
        
        if($hydrant){
            echo "{ fieldid:" . $_GET['fieldid'] . ", hy:" . $hydrant->hy . "}";
            header('Content-Type: text/plain');   
        } else {
            http_response_code(404);
        }
    } else {
        http_response_code(400);
    }
}

?>