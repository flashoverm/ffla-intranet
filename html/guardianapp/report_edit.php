<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . '/mail_controller.php';
require_once LIBRARY_PATH . '/file_create.php';

require_once LIBRARY_PATH . '/class/EventReport.php';
require_once LIBRARY_PATH . '/class/ReportUnit.php';
require_once LIBRARY_PATH . '/class/ReportUnitStaff.php';

$eventtypes = get_eventtypes ();
$staffpositions = get_staffpositions();
$engines = $engineDAO->getEngines();

// Pass variables (as an array) to template
$variables = array (
		'secured' => false,
		'eventtypes' => $eventtypes,
		'staffpositions' => $staffpositions,
		'engines' => $engines,
        'title' => "Wachbericht erstellen",
);

if(isset($_GET['id'])){
	$variables['secured'] = true;
	
	$uuid = trim($_GET['id']);
	$eventReport = get_report_object($uuid);
	if($eventReport){
		$variables['object'] = $eventReport;
		$variables['title'] = 'Wachbericht bearbeiten';
	} else {
		$variables ['alertMessage'] = "Wachbericht nicht gefunden";
	}
	
} else if(isset($_GET['event'])){
	
	$event = get_event($_GET['event']);
	if($event != null){
		
		if(userLoggedIn()){
			$creator = $userController->getCurrentUser()->getFullName();
		} else {
			$creator = "";
		}
		
		$staff = get_staff($_GET['event']);
		$eventReport = EventReport::fromEvent($event, $staff, $creator);
		$variables['object'] = $eventReport;

	} else {
		$variables ['alertMessage'] = "Wache nicht gefunden";
	}
}

if (isset($_POST) && isset($_POST ['start'])) {
       
    $date = trim ( $_POST ['date'] );
    $beginn = trim ( $_POST ['start'] );
    $end = trim ( $_POST ['end'] );
    $type = trim ( $_POST ['type'] );
    
    $typeOther = null;
    if(isset( $_POST ['typeOther'] ) && !empty( $_POST ['typeOther'] ) ){
        $typeOther = trim( $_POST ['typeOther'] );
    }
    $title = trim ( $_POST ['title'] );
    if(empty ($title)){
        $title = null;
    }
    $engine = trim ($_POST ['engine']);
    $noIncidents = false;
    if(isset($_POST ['noIncidents'])){
        $noIncidents = true;
    }
    $ilsEntry = false;
    if(isset($_POST ['ilsEntry'])){
        $ilsEntry = true;
    }
    $reportText = "";
    if (isset ( $_POST ['report'] )) {
    	$reportText = trim ( $_POST ['report'] );
    }
    $creator = trim ($_POST ['creator']);
        
    if(isset($_GET['id'])){
    	$eventReport->updateReport($date, $beginn, $end, $type, $typeOther,
    			$title, $engine, $noIncidents, $reportText, $creator, $ilsEntry);
    } else {
    	$eventReport = new EventReport($date, $beginn, $end, $type, $typeOther,
    			$title, $engine, $noIncidents, $reportText, $creator, $ilsEntry);
    	if(isset($_GET['event'])){
    	    $eventReport->event = $_GET['event'];
    	}
    }

    $unitCount = 1;
    while ( isset ( $_POST ["unit" . $unitCount . "unit"] ) ) {
        $unitdate = trim ( $_POST ['unit' . $unitCount . 'date' . "field"] );
        $unitbeginn = trim ( $_POST ['unit' . $unitCount . 'start' . "field"] );
        $unitend = trim ( $_POST ['unit' . $unitCount . 'end' . "field"] );
        $unitname = trim ( $_POST ['unit' . $unitCount . 'unit'] );
        
        $unit = new ReportUnit($unitname, $unitdate, $unitbeginn, $unitend);
        if(isset ( $_POST ['unit' . $unitCount . 'km'] ) && $_POST ['unit' . $unitCount . 'km'] != ""){
            $unitkm = trim ( $_POST ['unit' . $unitCount . 'km'] );
            $unit->setKM($unitkm);
        }
        
        $position = 1;
        while ( isset ( $_POST ["unit" . $unitCount . "function" . $position . "field"] ) ) {
            $function = trim ( $_POST ["unit" . $unitCount . "function" . $position . "field"] );
            $name = trim ( $_POST ["unit" . $unitCount . "name" . $position . "field"] );
            $engineUnit = trim ( $_POST ["unit" . $unitCount . "engine" . $position . "field"] );

            $unit->addStaff(new ReportUnitStaff($function, $name, $engineUnit));
            
            $position += 1;
        }
        
        $eventReport->addUnit($unit);
        $unitCount += 1;
    }
       
    $ok = false;
    if(isset($_GET['id'])){
        //Update        
    	if(update_report($eventReport)){
    		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportUpdated, $eventReport->uuid));
    		if(!createReportFile($uuid)){
    			if(mail_update_report ($eventReport)){
    				$variables ['successMessage'] = "Aktualisierter Bericht versendet";
    				$ok = true;
    			} else {
    				$variables ['alertMessage'] = "Bericht konnte nicht versendet werden - keine zuständigen Wachbeauftragten";
    			}
    		} else {
    			$variables ['alertMessage'] = "Aktualisiertes PDF konnnte nicht erstellt werden";
    		}
    	} else {
    		$variables ['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
    	}
    } else {
    	//Insert
    	$uuid = insert_report($eventReport);
    	if($uuid){
    		$eventReport->uuid = $uuid;
    		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportCreated, $eventReport->uuid));
    		if(!createReportFile($uuid)){
    			if(mail_insert_event_report ($eventReport)){
    				$variables ['successMessage'] = "Bericht versendet";
    				$ok = true;
    			} else {
    				$variables ['alertMessage'] = "Bericht konnte nicht versendet werden - keine zuständigen Wachbeauftragten";
    			}
    		} else {
    			$variables ['alertMessage'] = "PDF konnnte nicht erstellt werden";
    		}
    	} else {
    		$variables ['alertMessage'] = "Bericht konnte nicht gespeichert";
    	}
    }
    
    if($ok){
    	header ( "Location: " . $config["urls"]["guardianapp_home"] . "/reports/" . $uuid ); // redirects
    }
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "reportEdit/reportEdit_template.php", $variables );

?>