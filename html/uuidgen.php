<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );

if(isset($_GET["num"])){
    for($i=0; $i<$_GET["num"]; $i++){
        echo getGUID() . "<br>";
    }
    
} else {
    
    for($i=0; $i<100; $i++){
        echo getGUID() . "<br>";
    }
}
