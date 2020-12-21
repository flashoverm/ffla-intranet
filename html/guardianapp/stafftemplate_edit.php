<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

$variables = array (
		'title' => "Personalvorlagen",
		'secured' => true,
		'privilege' => Privilege::EVENTADMIN,
		'eventtypes' => $eventTypeDAO->getEventTypes(),
		'staffpositions' => $staffPositionDAO->getStaffPositions(),
);

if(isset($_GET ['eventtype'])){
	
	$eventtype_uuid = $_GET ['eventtype'];
	$eventType = $eventTypeDAO->getEventType($eventtype_uuid);
	
	$template = $staffTemplateDAO->getStaffTemplate($eventtype_uuid);
	if( ! $template){
		$template = new StaffTemplate();
		$template->setEventType($eventType);
	}
	
	if(isset($_POST ['positionCount'])){

		$template->clearStaffpositions();
		
		$count = $_POST ['positionCount'];
		for ($i = 0; $i <= $count; $i++) {
			if(isset($_POST ["staff" . $i])){
				$template->addStaffposition($staffPositionDAO->getStaffPosition($_POST ["staff" . $i]));
			}
		}
		
		$staffTemplateDAO->save($template);
	}
	
	$variables ['template'] = $template;
	$variables ['subtitle'] = $eventType->getType();
			
}	


renderLayoutWithContentFile ($config["apps"]["guardian"], "templateEdit_template.php", $variables );
