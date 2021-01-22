<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );


$db_config = $config['db'];

$today = date('Y-m-d', time());

$db_file = $config['paths']['backup'] . "db_dumps/" . $db_config['dbname'] . "_" . $today;

$cmd = "mysqldump " 
		. " --user=" . $db_config['username']
		. " --password=" . $db_config['password']
		. " --host=" . $db_config['host']
		. " " . $db_config['dbname']
		. " > " . $db_file . ".sql";

exec($cmd);

$cmd = "zip " . $db_file . ".zip " . $db_file . ".sql";

exec($cmd);

$backup_data = array(
		$config['paths']['files'],
		$config['paths']['confirmations'],
		$config['paths']['inspections'],
		$config['paths']['reports'],
		$config['paths']['confirmations'],
);


$pcloudController = new PCloudController();
$pcloudController->uploadBackup($filepath);