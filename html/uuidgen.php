<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );

if(isset($_GET["num"])){
    for($i=0; $i<$_GET["num"]; $i++){
        echo getUuid() . "<br>";
    }
    
} else {
    
    for($i=0; $i<100; $i++){
    	echo getUuid() . "<br>";
    }
}