<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_staffpositions.php";
require_once LIBRARY_PATH . "/db_staff_template.php";

$variables = array (
		'title' => "Personalvorlagen",
		'secured' => true,
		'privilege' => EVENTADMIN
);


$eventtypes = get_eventtypes ();
$variables ['eventtypes'] = $eventtypes;

if(isset($_GET ['eventtype'])){
	
	$eventtype_uuid = $_GET ['eventtype'];
	
	$template = get_staff_template($eventtype_uuid);
	$variables ['template'] = $template;

	if(isset($_POST ['positionCount'])){
		
		foreach($template as $entry):
			if(!isset($_POST [$entry->template])){
				delete_template_entry($entry->template);
			} else {
				update_template_entry($entry->template, $_POST [$entry->template]);
			}
		endforeach;
		
		$count = $_POST ['positionCount'];
		for ($i = 0; $i <= $count; $i++) {
			if(isset($_POST ["staff" . $i])){
				insert_template ( $eventtype_uuid, $_POST ["staff" . $i]);
			}
		}
		
		$template = get_staff_template($eventtype_uuid);
		$variables ['template'] = $template;
	}
	
	$eventtype = get_eventtype($eventtype_uuid);
	$variables ['subtitle'] = $eventtype->type;
			
	$staffpositions = get_staffpositions();
	$variables ['staffpositions'] = $staffpositions;	
}	


renderLayoutWithContentFile ($config["apps"]["guardian"], "templateEdit_template.php", $variables );
