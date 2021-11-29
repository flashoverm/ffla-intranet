<?php

require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );

echo "********************\nReindex confirmations\n********************\n";
foreach($confirmationDAO->getConfirmations() as $element){
	echo $element->getUuid() . "\n";
	$confirmationDAO->save($element);
	break;
}

?>