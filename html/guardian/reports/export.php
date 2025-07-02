<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

$eventtypes = $eventTypeDAO->getEventTypes();

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["guardian"],
		'template' => "reportExport_template.php",
	    'title' => "Export Wachberichte",
	    'secured' => true,
	    'eventtypes' => $eventtypes,
		'privilege' => Privilege::EVENTMANAGER,
);
checkSitePermissions($variables);

$type = -1;
$from = date('Y-m-01');
$to = date('Y-m-t');

if(SessionUtil::userLoggedIn()){
    $usersEngine = $userController->getCurrentUser()->getEngine();
    $_GET[ResultSet::SHOWALL_PARAM] = true;
        
    if(isset($_POST['from'])){
        $type = $_POST['type'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        
    }
    
    if( $usersEngine->getIsAdministration()){
        $reports = $reportDAO->getReportsFiltered($_GET, $type, $from, $to, "ASC");
    } else {
        $reports = $reportDAO->getReportsByEngineFiltered($usersEngine->getUuid(), $type, $from, $to, $_GET, "ASC");
        $variables ['infoMessage'] = "Es werden nur Wachberichte angezeigt, die Ihrem Zug zugewiesen wurden";
    }

    $variables ['type'] = $type;
    $variables ['from'] = $from;
    $variables ['to'] = $to;
    $variables ['reports'] = $reports;
   
}

if((isset($_POST['csv']) || isset($_POST['invoice']) || isset($_POST['csvlist'])) && $userController->hasCurrentUserPrivilege($variables ['privilege'])){
    
    $filename = "Wachberichte_Export.csv";
    if (isset($_POST['invoice'])){
        $filename = "Wachberichte_Export_Rechnung.csv";
    } else if (isset($_POST['csvlist'])){
        $filename = "Wachberichte_Export_Liste.csv";
    }
	
	header('Content-Encoding: UTF-8');
	header('Content-type: text/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename=' . $filename);
	
    if($type == -1 ){
        $head = "Alle Wachen";
    } else if ($type == -2) {
    	$head = "Alle Theaterwachen";
    } else {
    	$head = "Wachen von Typ " . $eventTypeDAO->getEventType($type)->getType();
    }
    $head .= " zwischen " . 
        date($config ["formats"] ["date"], strtotime($from)) . " und " . 
        date($config ["formats"] ["date"], strtotime($to)) . "\n\n";
        
    if(isset($_POST['csv'])){
    	reportsToCSV($reports->getData(), $head);
    } else if (isset($_POST['invoice'])){
    	reportsToInvoiceCSV($reports->getData(), $head);
    } else if (isset($_POST['csvlist'])){
        reportsToCSVList($reports->getData(), $head);
    }
    
    $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportsExported, null));

    return;
}

renderLayoutWithContentFile ( $variables );

function reportsToCSV($reports, $head = ""){
	global $config;
    $delimiter = ";";
    $filestring = $head;
        
    foreach ( $reports as $report ) {
        
        $filestring .= "Datum" . $delimiter .
        "Beginn" . $delimiter .
        "Ende" . $delimiter .
        "Veranstaltungsdauer" . $delimiter . 
        "Gesamtpersonalstunden" . $delimiter . 
        "Typ" . $delimiter .
        "Titel" . $delimiter .
        "\n";

        $duration = strtotime($report->getEndTime()) - strtotime($report->getStartTime());
        if($duration < 0){
            $duration = $duration + 86400;
        }
        $personalhours = 0;
        foreach ( $report->getUnits() as $unit ) {
        	$unitDuration = strtotime($unit->getEndTime()) - strtotime($unit->getStartTime());
        	if($unitDuration < 0){
        	    $unitDuration = $unitDuration + 86400;
        	}
       		$personalhours += ($unitDuration * count($unit->getStaff()));
        }
        
        $filestring .= date($config ["formats"] ["date"], strtotime($report->getDate())) . $delimiter . 
        date($config ["formats"] ["time"], strtotime($report->getStartTime())) . $delimiter . 
            date($config ["formats"] ["time"], strtotime($report->getEndTime())) . $delimiter . 
            DateFormatUtil::formatSecondsToHHMM($duration) . $delimiter . 
            DateFormatUtil::formatSecondsToHHMM($personalhours) . $delimiter .
            $report->getType()->getType() . $delimiter .
            $report->getTitle() . $delimiter . 
            "\n\nPersonal:\n";
                    
		foreach ( $report->getUnits() as $unit ) {
			$unitDuration = strtotime($unit->getEndTime()) - strtotime($unit->getStartTime());
			if($unitDuration < 0){
			    $unitDuration = $unitDuration + 86400;
			}
			foreach ( $unit->getStaff() as $staff ) {
			    
			    if($staff->getUser() != null){
			        $filestring .=  $staff->getUser()->getFullNameLastNameFirst() . $delimiter . 
			        $staff->getUser()->getEngine()->getName() . $delimiter;
			    } else {
			        $filestring .=  $staff->getName() . $delimiter . 
			        $staff->getEngine()->getName() . $delimiter;
			    }
			    
			    $filestring .= DateFormatUtil::formatSecondsToHHMM($unitDuration) .
                    "\n";
            }
        }
        $filestring .= "\n\n"; 
    }
    
    echo StringUtil::convertToWindowsCharset($filestring);
}

function reportsToInvoiceCSV($reports, $head = ""){
	global $config;
	$delimiter = ";";
	$filestring = $head;
	
	$filestring .= "Datum" . $delimiter .
	"Beginn" . $delimiter .
	"Ende" . $delimiter .
	"Veranstaltungsdauer" . $delimiter .
	"Titel" . $delimiter .
	"Personal" . $delimiter .
	"Gesamtpersonalstunden" . $delimiter .
	$delimiter ."Personal" . $delimiter .
	"\n";
	
	foreach ( $reports as $report ) {
		
		$duration = strtotime($report->getEndTime()) - strtotime($report->getStartTime());
		if($duration < 0){
		    $duration = $duration + 86400;
		}
		$personalhours = 0;
		$personalcount = 0;
		$personalString = "";
		foreach ( $report->getUnits() as $unit ) {
			$unitDuration = strtotime($unit->getEndTime()) - strtotime($unit->getStartTime());
			if($unitDuration < 0){
			    $unitDuration = $unitDuration + 86400;
			}
			$personalhours += ($unitDuration * count($unit->getStaff()));
			$personalcount += count($unit->getStaff());
			
			foreach($unit->getStaff() as $staff){
			    if($staff->getUser() != null){
			        $personalString .=  $staff->getUser()->getFullNameLastNameFirst() . $delimiter;
			    } else {
			        $personalString .=  $staff->getName() . $delimiter;
			    }
			}
		}
		
		$filestring .= date($config ["formats"] ["date"], strtotime($report->getDate())) . $delimiter .
		date($config ["formats"] ["time"], strtotime($report->getStartTime())) . $delimiter .
		date($config ["formats"] ["time"], strtotime($report->getEndTime())) . $delimiter .
		DateFormatUtil::formatSecondsToHHMM($duration) . $delimiter .
		$report->getTitle() . $delimiter .
		$personalcount . $delimiter .
		DateFormatUtil::formatSecondsToHHMM($personalhours) . $delimiter .
		$delimiter .$personalString . "\n";
	}
	
	echo StringUtil::convertToWindowsCharset($filestring);
}

function reportsToCSVList($reports, $head = ""){
    global $config;
    $delimiter = ";";
    $filestring = $head;
    
    $filestring .= "Datum" . $delimiter .
    "Beginn" . $delimiter .
    "Ende" . $delimiter .
    "Typ" . $delimiter .
    "Titel" . $delimiter .
    "Personal" . $delimiter .
    "Löschzug" . $delimiter .
    "Dauer" . $delimiter .
    "\n";
    
    foreach ( $reports as $report ) {
                
        foreach ( $report->getUnits() as $unit ) {
            $unitDuration = strtotime($unit->getEndTime()) - strtotime($unit->getStartTime());
            if($unitDuration < 0){
                $unitDuration = $unitDuration + 86400;
            }
            foreach ( $unit->getStaff() as $staff ) {
                
                $filestring .= date($config ["formats"] ["date"], strtotime($report->getDate())) . $delimiter .
                date($config ["formats"] ["time"], strtotime($report->getStartTime())) . $delimiter .
                date($config ["formats"] ["time"], strtotime($report->getEndTime())) . $delimiter .
                $report->getType()->getType() . $delimiter .
                $report->getTitle() . $delimiter;
                
                if($staff->getUser() != null){
                    $filestring .= $staff->getUser()->getFullNameLastNameFirst() . $delimiter . 
                    $staff->getUser()->getEngine()->getName() . $delimiter;
                } else {
                    $filestring .= $staff->getName() . $delimiter .
                    $staff->getEngine()->getName() . $delimiter;
                }
                
                $filestring .= DateFormatUtil::formatSecondsToHHMM($unitDuration) .
                "\n";
            }
        }
    }
    
    echo StringUtil::convertToWindowsCharset($filestring);
}

?>