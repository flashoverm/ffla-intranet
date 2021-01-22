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

echo " \n \nCreating mysql dump \n";
echo $db_file . ".sql\n";

exec($cmd);

$cmd = "zip " . $db_file . ".zip " . $db_file . ".sql";

echo " \n \nZipping mysql dump: \n";
echo $db_file . ".zip\n";

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

echo " \n \nZipping data: \n";
echo $data_file . ".zip\n";

exec($cmd);

$pcloudController = new PCloudController();

echo " \n \nUploading dump file (zip) \n";
$pcloudController->uploadBackup($db_file . ".zip");

echo " \n \nUploading data file (zip) \n";
$pcloudController->uploadBackup($data_file);