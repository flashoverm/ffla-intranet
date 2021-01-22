<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );


$db_config = $config['db'];

$today = date('Y-m-d', time());

$db_file = $config['paths']['backup'] . "db_dumps/" . $db_config['dbname'] . "_" . $today;
$data_file = $config['paths']['backup'] . "db_dumps/data_" . $today . ".zip";

$cmd = "mysqldump " 
		. " --user=" . $db_config['username']
		. " --password=" . $db_config['password']
		. " --host=" . $db_config['host']
		. " " . $db_config['dbname']
		. " > " . $db_file . ".sql";

echo "Creating mysql dump";
exec($cmd);

$cmd = "zip " . $db_file . ".zip " . $db_file . ".sql";

echo "Zipping mysql dump";
exec($cmd);

$backup_data = array(
		$config['paths']['files'],
		$config['paths']['confirmations'],
		$config['paths']['inspections'],
		$config['paths']['reports'],
		$config['paths']['confirmations'],
);

$cmd = "zip -R" . $data_file . " ";
foreach($backup_data as $path){
	$cmd .= $path . " ";
}

echo "Zipping data";
exec($cmd);

$pcloudController = new PCloudController();

echo "Uploading dump file (zip)";
$pcloudController->uploadBackup($db_file . ".zip");

echo "Uploading data file (zip)";
$pcloudController->uploadBackup($data_file);