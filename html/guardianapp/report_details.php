<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => 'Wachbericht',
		'secured' => true,
		'showFormular' => false,
);

if (! isset($_GET['id'])) {
	
	$variables['title'] = "Bericht kann nicht angezeigt werden";
	$variables['alertMessage'] = "Bericht kann nicht angezeigt werden";

} else {
	
	$uuid = trim($_GET['id']);
	$report = get_report($uuid);
	$units = get_report_units($uuid);
	
	if($report){
		
		$variables['report'] = $report;
		$variables['units'] = $units;
				
		if(userLoggedIn()){
        	
			$currentUser = $userController->getCurrentUser();
			if( $guardianUserController->isUserAllowedToEditReport($currentUser, $uuid) ){
	            
	        	$variables['showFormular'] = true;
	            
	            if(isset($_POST['emsEntry'])){
	            	if(set_ems_entry($uuid)){
	                    $variables['successMessage'] = "Bericht aktualisiert";
	                    $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportEMSSet, $uuid));
	                } else {
	                    $variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
	                }
	                $variables['report'] = get_report($uuid);
	            }
	            
	            if(isset($_POST['emsEntryRemoved'])){
	            	if(delete_ems_entry($uuid)){
	            		$variables['successMessage'] = "Bericht aktualisiert";
	            		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportEMSUnset, $uuid));
	            	} else {
	            		$variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
	            	}
	            	$variables['report'] = get_report($uuid);
	            }
	            
	            if(isset($_POST['managerApprove'])){
	            	if(set_approval($uuid)){
	            		mail_report_approved($uuid);
	            		$variables['successMessage'] = "Bericht aktualisiert und an Verwaltung versandt";
	            		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportApproved, $uuid));
	            	} else {
	            		$variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
	            	}
	            	$variables['report'] = get_report($uuid);
	            }
	            
	            if(isset($_POST['managerApproveRemove'])){
	            	if(delete_approval($uuid)){
	            		$variables['successMessage'] = "Bericht aktualisiert";
	            		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportApprovRemoved, $uuid));
	            	} else {
	            		$variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
	            	}
	            	$variables['report'] = get_report($uuid);
	            }
	            
	            if (isset ( $_POST ['delete'] )) {
	            	$log = LogbookEntry::fromAction(LogbookActions::ReportDeleted, $uuid);
	            	if(delete_report ( $uuid )){
	            		$variables ['successMessage'] = "Bericht gelöscht";
	            		$logbookDAO->save($log);
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

if(isset($_GET['print'])){
	
	$variables['showFormular'] = true;
	$variables['orientation'] = 'portrait';
	renderPrintContentFile($config["apps"]["guardian"], "reportDetails/reportPDF_template.php", $variables);
	
} else if( isset($_GET['id']) && isset($_GET['file']) ) {
	
	$uuid = $_GET['id'];
	$fullpath = $config["paths"]["reports"] . basename($uuid) . ".pdf";
	$dl_filename = "Wachbericht_" . $uuid . ".pdf";
	getFileResponse($fullpath, "createReportFile", $uuid, $dl_filename);
	
} else {
	
	renderLayoutWithContentFile($config["apps"]["guardian"], "reportDetails/reportDetails_template.php", $variables);
	
}