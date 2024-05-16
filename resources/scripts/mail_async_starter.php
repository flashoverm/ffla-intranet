<?php

require_once realpath ( dirname ( __FILE__ ) . "/../config/config.php" );

echo trim(shell_exec('ps -ef | grep -v grep | grep mail_async_process.php'));

if(trim(shell_exec('ps -ef | grep -v grep | grep mail_async_process.php')) == '') {
    
	echo "mail_async_process not running - starting";
	
	exec('nohup ' . $config["paths"]["php"] . ' mail_async_process.php >> /dev/null &');
}