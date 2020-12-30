<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . '/mail_controller.php';
require_once LIBRARY_PATH . '/file_create.php';

$eventtypes = $eventTypeDAO->getEventTypes();
$staffpositions = $staffPositionDAO->getStaffPositions();
$engines = $engineDAO->getEngines();

// Pass variables (as an array) to template
$variables = array (
		'secured' => false,
		'eventtypes' => $eventtypes,
		'staffpositions' => $staffpositions,
		'engines' => $engines,
        'title' => "Wachbericht erstellen",
);

$eventReport = NULL;

if(isset($_GET['id'])){
	$variables['secured'] = true;
	
	$uuid = trim($_GET['id']);
	$eventReport = $reportDAO->getReport($uuid);
	if($eventReport){
		$variables['report'] = $eventReport;
		$variables['title'] = 'Wachbericht bearbeiten';
	} else {
		$variables ['alertMessage'] = "Wachbericht nicht gefunden";
	}
	
} else if(isset($_GET['event'])){
	
	$event = $eventDAO->getEvent($_GET['event']);
	if($event){
		
		if(userLoggedIn()){
			$creator = $userController->getCurrentUser()->getFullName();
		} else {
			$creator = "";
		}
		
		$eventReport = new Report();
		$eventReport->setDataOfEvent($event);
		$eventReport->setCreator($creator);
		$variables['report'] = $eventReport;

	} else {
		$variables ['alertMessage'] = "Wache nicht gefunden";
	}
}

if (isset($_POST) && isset($_POST ['start'])) {
       
    $date = trim ( $_POST ['date'] );
    $beginn = trim ( $_POST ['start'] );
    $end = trim ( $_POST ['end'] );
    $typeUuid = trim ( $_POST ['type'] );
    $type = $eventTypeDAO->getEventType($typeUuid);
    
    $typeOther = null;
    if(isset( $_POST ['typeOther'] ) && !empty( $_POST ['typeOther'] ) ){
        $typeOther = trim( $_POST ['typeOther'] );
    }
    $title = trim ( $_POST ['title'] );
    if(empty ($title)){
        $title = null;
    }
    $engineUuid = trim ($_POST ['engine']);
    $engine = $engineDAO->getEngine($engineUuid);
    
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
        
    if( $eventReport == NULL ){
    	$eventReport = new Report();
    	if(isset($_GET['event'])){
    		$eventReport->setEventUuid($_GET['event']);
    	}
    }
    
    $eventReport->setReportData($date, $beginn, $end, $type, $typeOther,
    		$title, $engine, $noIncidents, $reportText, $creator, $ilsEntry);
    $eventReport->clearReportUnits();

    $unitCount = 1;
    while ( isset ( $_POST ["unit" . $unitCount . "unit"] ) ) {
        $unitdate = trim ( $_POST ['unit' . $unitCount . 'date' . "field"] );
        $unitbeginn = trim ( $_POST ['unit' . $unitCount . 'start' . "field"] );
        $unitend = trim ( $_POST ['unit' . $unitCount . 'end' . "field"] );
        $unitname = trim ( $_POST ['unit' . $unitCount . 'unit'] );
        
        $unit = new ReportUnit($unitname, $unitdate, $unitbeginn, $unitend);
        if(isset ( $_POST ['unit' . $unitCount . 'km'] ) && $_POST ['unit' . $unitCount . 'km'] != ""){
            $unitkm = trim ( $_POST ['unit' . $unitCount . 'km'] );
            $unit->setKm($unitkm);
        }
        
        $position = 1;
        while ( isset ( $_POST ["unit" . $unitCount . "function" . $position . "field"] ) ) {
            $staffPositionUuid = trim ( $_POST ["unit" . $unitCount . "function" . $position . "field"] );
            $staffPosition = $staffPositionDAO->getStaffPosition($staffPositionUuid);
            $name = trim ( $_POST ["unit" . $unitCount . "name" . $position . "field"] );
            $unitEngineUuid = trim ( $_POST ["unit" . $unitCount . "engine" . $position . "field"] );
            $engineUnit = $engineDAO->getEngine($unitEngineUuid);

            $unit->addStaff(new ReportStaff($staffPosition, $name, $engineUnit));
            
            $position += 1;
        }
        
        $eventReport->addReportUnit($unit);
        $unitCount += 1;
    }
    
    $eventReport = $reportDAO->save($eventReport);
       
    $ok = false;
    if(isset($_GET['id'])){
        //Update        
    	if($eventReport){
    		$variables['report'] = $eventReport;
    		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportUpdated, $eventReport->getUuid()));
    		if(!createReportFile($eventReport->getUuid())){
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
    	if($eventReport){
    		$variables['report'] = $eventReport;
    		$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportCreated, $eventReport->getUuid()));
    		if(!createReportFile($eventReport->getUuid())){
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
    	//header ( "Location: " . $config["urls"]["guardianapp_home"] . "/reports/" . $eventReport->getUuid() ); // redirects
    }
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "reportEdit/reportEdit_template.php", $variables );

?>