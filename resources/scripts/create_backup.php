<?php 
/**
 *	Create crontab entry: 
 *  0 1 * * * /usr/bin/php /var/www/ffla-intranet/resources/scripts/create_backup.php
 * 
 */

require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );

$db_config = $config['db'];

$today = date('Y-m-d', time());

$db_dir = $config['paths']['backup'] . "db_dumps";
$db_file = gethostname ( ) . "_db_". $db_config['dbname'] . "_" . $today;

$data_file = $config['paths']['backup'] . "/" . gethostname ( ) . "_data_" . $today . ".zip";

$cmd = "mysqldump " 
		. " --user=" . $db_config['username']
		. " --password=" . $db_config['password']
		. " --host=" . $db_config['host']
		. " " . $db_config['dbname']
		. " > " . $db_dir . "/" . $db_file . ".sql";

echo " \n \nCreating mysql dump \n";
echo $db_file . ".sql\n";
echo "Executing " . $cmd . "\n";
exec($cmd);

$cmd = "cd " . $db_dir . " && zip " . $db_file . ".zip " . $db_file . ".sql";

echo " \n \nZipping mysql dump: \n";
echo $db_file . ".zip\n";
echo "Executing " . $cmd . "\n";

exec($cmd);

$backup_data = array(
		$config['paths']['files'],
);

$cmd = "cd " . $config['paths']['data'] . " && zip -r " . $data_file . " ";
foreach($backup_data as $path){
	$folder = substr($path, 0, -1);
	$folder = substr($folder, strrpos($folder, '/') + 1);
	$cmd .= $folder . " ";
}

echo " \n \nZipping data: \n";
echo $data_file . ".zip\n";
echo "Executing " . $cmd . "\n";

exec($cmd);

$pcloudController = new PCloudController();

echo " \n \nUploading dump file (zip) \n";
$pcloudController->uploadBackup($db_dir . "/" . $db_file . ".zip");

echo " \n \nUploading data file (zip) \n";
$pcloudController->uploadBackup($data_file);