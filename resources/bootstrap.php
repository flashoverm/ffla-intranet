<?php

session_start ();

require __DIR__ . '/vendor/autoload.php';

require_once realpath ( dirname ( __FILE__ ) . "/config/config.php" );

foreach (glob( MODELS_PATH . "/*.php") as $filename) {
	include_once $filename;
}

foreach (glob( LIBRARY_PATH . "/util/*.php") as $filename) {
	include_once $filename;
}

foreach (glob( LIBRARY_PATH . "/mail/*.php") as $filename) {
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

//PDO 

$db = BaseDAO::getPDO();

//DAOs

$privilegeDAO = new PrivilegeDAO($db);
$engineDAO = new EngineDAO($db);

$userPrivilegeDAO = new UserPrivilegeDAO($db, $privilegeDAO, $engineDAO);
$userDAO = new UserDAO($db, $userPrivilegeDAO, $engineDAO);

$tokenDAO = new TokenDAO($db, $userDAO);
$mailLogDAO = new MailLogDAO($db);
$logbookDAO = new LogbookDAO($db);

$confirmationDAO = new ConfirmationDAO($db, $userDAO);
$dataChangeRequestDAO = new DataChangeRequestDAO($db, $userDAO);

$fileDAO = new FileDAO($db);

$hydrantDAO = new HydrantDAO($db, $engineDAO);
$inspectionDAO = new InspectionDAO($db, $hydrantDAO, $engineDAO);

$eventTypeDAO = new EventTypeDAO($db);
$staffPositionDAO = new StaffPositionDAO($db);
$staffTemplateDAO = new StaffTemplateDAO($db, $staffPositionDAO, $eventTypeDAO);

$staffDAO = new StaffDAO($db, $userDAO, $staffPositionDAO);
$eventDAO = new EventDAO($db, $userDAO, $engineDAO, $eventTypeDAO, $staffDAO);
$reportUnitDAO = new ReportUnitDAO($db, $userDAO, $staffPositionDAO, $engineDAO);
$reportDAO = new ReportDAO($db, $engineDAO, $eventTypeDAO, $reportUnitDAO, $userDAO);

$pagerAlertDAO = new PagerAlertDAO($db);

//Controller

$userController = new UserController($privilegeDAO, $userDAO, $tokenDAO);

$guardianUserController = new GuardianUserController($privilegeDAO, $userDAO, $tokenDAO);
$eventController = new EventController($eventDAO, $staffDAO, $userDAO, $userController);
$reportController = new ReportController($reportDAO);

$hydrantController = new HydrantController($hydrantDAO);

$confirmationController = new ConfirmationController($confirmationDAO);
$dataChangeRequestController = new DataChangeRequestController($dataChangeRequestDAO);

//Load current user
$currentUser = $userController->getCurrentUser();
