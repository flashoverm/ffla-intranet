<?php
if(!isset($events) ){
	//Disabled
} else if ( ! count ( $events )) {
	showInfo ( "Es sind keine öffentlichen Wachen vorhanden" );
} else {
	
	$options = array(
			'showOccupation' => true,
	);
	
	render(TEMPLATES_PATH . "/guardianapp/elements/event_table.php", $events, $options);
}
?>