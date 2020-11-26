<?php
require_once realpath(__DIR__ . "/baseConfig.php");
require_once realpath(__DIR__ . "/config.php");
require_once realpath(__DIR__ . "/vendor/autoload.php");

require_once LIBRARY_PATH . '/util.php';
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_logbook.php";
require_once LIBRARY_PATH . "/class/constants/LogbookActions.php";

require_once MODELS_PATH . "/Engine.php";
require_once MODELS_PATH . "/Privilege.php";
require_once MODELS_PATH . "/User.php";

require_once REPOSITORIES_PATH . "/EngineDAO.php";
require_once REPOSITORIES_PATH . "/PrivilegeDAO.php";

require_once CONTROLLER_PATH . "/UserController.php";


use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


$isDevMode = true;
$paths = array(__DIR__ . "/models");

$emconfig = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

$dbParams = array(
		'driver'   => 'pdo_mysql',
		'user'     => $config ['db'] ['username'],
		'password' => $config ['db'] ['password'],
		'dbname'   => $config ['db'] ['dbname'] . "",
);


$entityManager = EntityManager::create($dbParams, $emconfig);

$userController = new UserController($entityManager);

$engineDAO = new EngineDAO($entityManager);
$privilegeDAO = new PrivilegeDAO($entityManager);
