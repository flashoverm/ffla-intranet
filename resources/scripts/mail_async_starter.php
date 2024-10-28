<?php

require_once realpath ( dirname ( __FILE__ ) . "/../config/config.php" );

echo "\n";

echo trim(shell_exec('ps -ef | grep -v grep | grep mail_async_process.php'));

echo "\n";

if(trim(shell_exec('ps -ef | grep -v grep | grep mail_async_process.php')) == '') {
    
	echo "mail_async_process not running - starting\n";
	
	$command = 'nohup ' . $config["paths"]["php"] . ' ' . dirname ( __FILE__ ) . '/mail_async_process.php >> /dev/null &';
	
	echo "\nCommand: " . $command  . "\n";
	
	exec($command);
}