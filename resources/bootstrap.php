<?php 
require_once realpath ( dirname ( __FILE__ ) . "/config/config.php" );

foreach (glob( MODELS_PATH . "/*.php") as $filename) {
	include_once $filename;
}

foreach (glob( REPOSITORIES_PATH . "/static/*.php") as $filename) {
	include_once $filename;
}

foreach (glob( LIBRARY_PATH . "/*.php") as $filename) {
	include_once $filename;
}

foreach (glob( REPOSITORIES_PATH . "/*.php") as $filename) {
	include_once $filename;
}

foreach (glob( CONTROLLER_PATH . "/*.php") as $filename) {
	include_once $filename;
}

$userDAO = new UserDAO();
$privilegeDAO = new PrivilegeDAO();
$engineDAO = new EngineDAO();
$logbookDAO = new LogbookDAO();
$mailLogDAO = new MailLogDAO();
$confirmationDAO = new ConfirmationDAO();

$userController = new UserController();
$guardianUserController = new GuardianUserController();
$confirmationController = new ConfirmationController();
