<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["guardian"],
		'template' => "reportDetails/reportDetails_template.php",
		'title' => 'Wachbericht',
		'secured' => true,
		'showFormular' => false,
);
checkSitePermissions($variables);

if (! isset($_GET['id'])) {
	
	$variables['title'] = "Bericht kann nicht angezeigt werden";
	$variables['alertMessage'] = "Bericht kann nicht angezeigt werden";

} else {
	
	$uuid = trim($_GET['id']);
	$report = $reportDAO->getReport($uuid);
	
	if($report){
		
		$variables['report'] = $report;
				
		if(SessionUtil::userLoggedIn()){
		    
		    $permissionArray = array(
		        array("privilege" => Privilege::FFADMINISTRATION),
		        array("privilege" => Privilege::EVENTADMIN),
		        array("privilege" => Privilege::EVENTMANAGER, "engine" => $report->getEngine()),
		        array("user" => $report->getCreator())
		    );
		    foreach ( $report->getStaff() as $staff ) {
		        if($staff->getUser() != null){
		            $permissionArray[] = array("user", $staff->getUser());
		        }
		    }
			
		    checkPermissions($permissionArray, $variables);
				            
        	$variables['showFormular'] = true;
        	
        	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        		checkPermissions(array(
        				array("privilege" => Privilege::EVENTADMIN),
        				array("privilege" => Privilege::EVENTMANAGER, "engine" => $report->getEngine()),
        		), $variables);
        	}
            
            if(isset($_POST['emsEntry'])){
            	if($reportController->setEmsEntry($uuid)){
                    $variables['successMessage'] = "Bericht aktualisiert";
                    $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportEMSSet, $uuid));
                } else {
                    $variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
                }
                $variables['report'] = $reportDAO->getReport($uuid);
            }
            
            if(isset($_POST['emsEntryRemoved'])){
            	if($reportController->unsetEmsEntry($uuid)){
            		$variables['successMessage'] = "Bericht aktualisiert";
            		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportEMSUnset, $uuid));
            	} else {
            		$variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
            	}
            	$variables['report'] = $reportDAO->getReport($uuid);
            }
            
            if(isset($_POST['managerApprove'])){
            	if($reportController->setApproval($uuid, $currentUser)){
            		mail_report_approved($uuid);
            		$variables['successMessage'] = "Bericht aktualisiert und an Verwaltung versandt";
            		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportApproved, $uuid));
            	} else {
            		$variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
            	}
            	$variables['report'] = $reportDAO->getReport($uuid);
            }
            
            if(isset($_POST['managerApproveRemove'])){
            	if($reportController->unsetApproval($uuid)){
            		$variables['successMessage'] = "Bericht aktualisiert";
            		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportApprovRemoved, $uuid));
            	} else {
            		$variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
            	}
            	$variables['report'] = $reportDAO->getReport($uuid);
            }
            
            if (isset ( $_POST ['delete'] )) {
            	$log = LogbookEntry::fromAction(LogbookActions::ReportDeleted, $uuid);
            	if($reportDAO->deleteReport( $uuid )){
            		$variables ['successMessage'] = "Bericht gelöscht";
            		$logbookDAO->save($log);
            		header ( "Location: " . $config["urls"]["guardianapp_home"] . "/reports/overview"); // redirects
            	} else {
            		$variables ['alertMessage'] = "Bericht konnte nicht gelöscht werden";
            	}
            }  
		}

	} else {
		
		$variables['alertMessage'] = "Bericht nicht gefunden";
		$variables['title'] = 'Bericht nicht gefunden';

	}
}

if(isset($_GET['print'])){
	
	$variables['app'] = $config["apps"]["guardian"];
	$variables['template'] = "reportDetails/reportPDF_template.php";
	$variables['showFormular'] = true;
	$variables['orientation'] = 'portrait';
	renderPrintContentFile($variables);
	
} else if( isset($_GET['id']) && isset($_GET['file']) ) {
	
	$uuid = $_GET['id'];
	$fullpath = $config["paths"]["reports"] . basename($uuid) . ".pdf";
	$dl_filename = "Wachbericht_" . $uuid . ".pdf";
	getFileResponse($fullpath, "createReportFile", $uuid, $dl_filename);
	
} else {
	
	renderLayoutWithContentFile($variables);
	
}