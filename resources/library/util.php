<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");

function showAlert($message) {
    echo "<div class=\"alert alert-danger\" role=\"alert\">" . $message . "</div>";
}

function showSuccess($message) {
    echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
}

function showInfo($message) {
    echo "<div class=\"alert alert-secondary\" role=\"alert\">" . $message . "</div>";
}

function goToLogin(){
    global $config;
    
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    $_SESSION["ref"] = $actual_link;
    header("Location: " . $config["urls"]["intranet_home"] . "/login"); // redirects
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    
    return (substr($haystack, -$length) === $needle);
}