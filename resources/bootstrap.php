<?php 
require_once realpath ( dirname ( __FILE__ ) . "/config/config.php" );

foreach (glob( MODELS_PATH . "/*.php") as $filename) {
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

//DAOs

$privilegeDAO = new PrivilegeDAO();
$engineDAO = new EngineDAO();
$logbookDAO = new LogbookDAO();
$mailLogDAO = new MailLogDAO();

$userDAO = new UserDAO($privilegeDAO, $engineDAO);

$confirmationDAO = new ConfirmationDAO($userDAO);

$fileDAO = new FileDAO();

$hydrantDAO = new HydrantDAO($engineDAO);
$inspectionDAO = new InspectionDAO($hydrantDAO, $engineDAO);

$eventTypeDAO = new EventTypeDAO();
$staffPositionDAO = new StaffPositionDAO();
$staffTemplateDAO = new StaffTemplateDAO($staffPositionDAO, $eventTypeDAO);

$staffDAO = new StaffDAO($userDAO, $staffPositionDAO);
$eventDAO = new EventDAO($userDAO, $engineDAO, $eventTypeDAO, $staffDAO);
$reportUnitDAO = new ReportUnitDAO($engineDAO, $staffPositionDAO);
$reportDAO = new ReportDAO($engineDAO, $eventTypeDAO, $reportUnitDAO);

//Controller

$userController = new UserController($privilegeDAO, $userDAO);
$guardianUserController = new GuardianUserController($privilegeDAO, $userDAO);
$confirmationController = new ConfirmationController($confirmationDAO);
$hydrantController = new HydrantController($hydrantDAO);
$eventController = new EventController($eventDAO, $staffDAO, $userDAO, $userController);
$reportController = new ReportController($reportDAO);