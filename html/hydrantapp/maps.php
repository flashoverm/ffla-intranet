<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once LIBRARY_PATH . "/util.php";

session_start ();

if(	userLoggedIn() ){
    
    $fullpath = $config["paths"]["maps"] . basename($_GET['hydrant']) . ".png";
    
    $im = imagecreatefrompng ( $fullpath );
    
    ob_end_clean();
    
    header('Content-Type: image/png');
    
    imagepng($im);
    
    imagedestroy($im);
} else {
    goToLogin();
}

?>
