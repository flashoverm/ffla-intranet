<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_report.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_staffpositions.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => 'Wachbericht',
		'privilege' => EVENTMANAGER,
		'secured' => true,
		'showFormular' => false,
		'alertMessage' => "Bericht kann nicht angezeigt werden"
);

if (! isset($_GET['id'])) {
	
	$variables['title'] = "Bericht kann nicht angezeigt werden";
	$variables['alertMessage'] = "Bericht kann nicht angezeigt werden";

} else {
	
	$uuid = trim($_GET['id']);
	$report = get_report($uuid);
	$units = get_report_units($uuid);
	
	if($report){
		
		get_report_object($uuid);
		
		if(isset($_SESSION ['guardian_userid'])){
        
	        $user = get_user($_SESSION ['guardian_userid']);
	        
	        if($report->engine == $user->engine || is_administration($user->engine) || is_admin($user->uuid)){
	            
	        	$variables['report'] = $report;
	        	$variables['units'] = $units;
	        	$variables['showFormular'] = true;
	            
	            if(isset($_POST['emsEntry'])){
	            	if(set_ems_entry($uuid)){
	                    $variables['successMessage'] = "Bericht aktualisiert";
	                } else {
	                    $variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
	                }
	                $variables['report'] = get_report($uuid);
	            }
	            
	            if(isset($_POST['emsEntryRemoved'])){
	            	if(delete_ems_entry($uuid)){
	            		$variables['successMessage'] = "Bericht aktualisiert";
	            	} else {
	            		$variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
	            	}
	            	$variables['report'] = get_report($uuid);
	            }
	            
	            if(isset($_POST['managerApprove'])){
	            	if(set_approval($uuid)){
	            		mail_report_approved($uuid);
	            		$variables['successMessage'] = "Bericht aktualisiert und an Verwaltung versandt";
	            	} else {
	            		$variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
	            	}
	            	$variables['report'] = get_report($uuid);
	            }
	            
	            if(isset($_POST['managerApproveRemove'])){
	            	if(delete_approval($uuid)){
	            		$variables['successMessage'] = "Bericht aktualisiert";
	            	} else {
	            		$variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
	            	}
	            	$variables['report'] = get_report($uuid);
	            }
	            
	            if (isset ( $_POST ['delete'] )) {
	            	if(delete_report ( $uuid )){
	            		$variables ['successMessage'] = "Bericht gelöscht";
	            		header ( "Location: " . $config["urls"]["guardianapp_home"] . "/reports"); // redirects
	            	} else {
	            		$variables ['alertMessage'] = "Bericht konnte nicht gelöscht werden";
	            	}
	            }
	            
	        } else {
	        	
	        	$variables['alertMessage'] = "Sie haben keine Zugriffsrechte auf diesen Bericht";
	        	$variables['title'] = 'Sie haben keine Zugriffsrechte auf diesen Bericht';

	        }   
		}

	} else {
		
		$variables['alertMessage'] = "Bericht nicht gefunden";
		$variables['title'] = 'Bericht nicht gefunden';

	}
}

renderLayoutWithContentFile($config["apps"]["guardian"], "reportDetails/reportDetails_template.php", $variables);
