<?php 
require_once realpath(dirname(__FILE__) . "/../../../../config.php");

if(isset($inspection) || isset($hydrants)){
	require_once 'inspectionTable.php';
} 
?>