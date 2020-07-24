<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));

function log_message($message) {
    global $config;

    //error_log(date("Y-m-d H:i:s") . " - " . $message . "\n", 3, $config["logdir"] . "guardian.log");   
}

?>
