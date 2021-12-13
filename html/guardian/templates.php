<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

$variables = array (
		'app' => $config["apps"]["guardian"],
		'template' => "templateEdit_template.php",
		'title' => "Personalvorlagen",
		'secured' => true,
		'privilege' => Privilege::EVENTADMIN,
		'eventtypes' => $eventTypeDAO->getEventTypes(),
		'staffpositions' => $staffPositionDAO->getStaffPositions(),
);
$variables = checkSitePermissions($variables);

if(isset($_GET ['eventtype'])){
	
	$eventtype_uuid = $_GET ['eventtype'];
	$eventType = $eventTypeDAO->getEventType($eventtype_uuid);
	
	$staffTemplate = $staffTemplateDAO->getStaffTemplate($eventtype_uuid);
	if( ! $staffTemplate){
		$staffTemplate = new StaffTemplate();
		$staffTemplate->setEventType($eventType);
	}
	
	if(isset($_POST ['positionCount'])){

		$staffTemplate->clearStaffpositions();
		
		$count = $_POST ['positionCount'];
		for ($i = 0; $i <= $count; $i++) {
			if(isset($_POST ["staff" . $i])){
				$staffTemplate->addStaffposition($staffPositionDAO->getStaffPosition($_POST ["staff" . $i]));
			}
		}
		
		$staffTemplateDAO->save($staffTemplate);
	}
	
	$variables ['staffTemplate'] = $staffTemplate;
	$variables ['subtitle'] = $eventType->getType();
			
}	

renderLayoutWithContentFile ($variables );
