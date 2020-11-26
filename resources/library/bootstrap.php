<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once LIBRARY_PATH . "/../vendor/autoload.php";

require_once LIBRARY_PATH . '/util.php';
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_logbook.php";
require_once LIBRARY_PATH . "/class/constants/LogbookActions.php";

require_once LIBRARY_PATH . "/models/Engine.php";
require_once LIBRARY_PATH . "/models/Privilege.php";
require_once LIBRARY_PATH . "/models/User.php";

require_once LIBRARY_PATH . "/repositories/UserDAO.php";


use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


$isDevMode = true;
$paths = array(__DIR__ . "/models");
echo $paths[0];

$emconfig = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

$dbParams = array(
		'driver'   => 'pdo_mysql',
		'user'     => $config ['db'] ['username'],
		'password' => $config ['db'] ['password'],
		'dbname'   => $config ['db'] ['dbname'] . "",
);



$entityManager = EntityManager::create($dbParams, $emconfig);

$userDao = new UserDAO($entityManager);
$users = $userDao->getUsers();
echo gettype($users) . " - " . count($users);

// $userRepository = $entityManager->getRepository('User');

// $criteria = array('employer_mail' => 'reinhard.busch@drv-bayernsued.de');
// $user = $userRepository->findBy($criteria);

// echo $user[0]->getFirstname();

 die();