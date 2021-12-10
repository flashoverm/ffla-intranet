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
$variables = checkPermissions($variables);

$type = -1;
$from = date('Y-m-01');
$to = date('Y-m-t');

if(userLoggedIn()){
    $usersEngine = $userController->getCurrentUser()->getEngine();
        
    if( $usersEngine->getIsAdministration() ){
    	$reports = $reportDAO->getReports($_GET, "ASC");
    } else {
    	$reports = $reportDAO->getReportsByEngine($usersEngine->getUuid(), $_GET, "ASC");
        $variables ['infoMessage'] = "Es werden nur Wachberichte angezeigt, die Ihrem Zug zugewiesen wurden";
    }
    
    if(isset($_POST['from'])){
        $type = $_POST['type'];
        $from = $_POST['from'];
        $to = $_POST['to'];

    }
    
    $reports = $reportDAO->filterReports($reports, $type, $from, $to);
    
    $variables ['type'] = $type;
    $variables ['from'] = $from;
    $variables ['to'] = $to;
    $variables ['reports'] = $reports;
   
}

if((isset($_POST['csv']) || isset($_POST['invoice'])) && $userController->hasCurrentUserPrivilege($variables ['privilege'])){
	
	header('Content-Encoding: UTF-8');
	header('Content-type: text/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename=Wachberichte_Export.csv');
	
    if($type == -1 ){
        $head = "Alle Wachen";
    } else {
    	$head = "Wachen von Typ " . $eventTypeDAO->getEventType($type)->getType();
    }
    $head .= " zwischen " . 
        date($config ["formats"] ["date"], strtotime($from)) . " und " . 
        date($config ["formats"] ["date"], strtotime($to)) . "\n\n";
        
    if(isset($_POST['csv'])){
    	reportsToCSV($reports, $head);
    } else if(isset($_POST['invoice'])){
    	reportsToInvoiceCSV($reports, $head);
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
        "Dauer" . $delimiter . 
        "Gesamtstunden" . $delimiter . 
        "Typ" . $delimiter .
        "Titel" . $delimiter .
        "\n";

        $duration = strtotime($report->getEndTime()) - strtotime($report->getStartTime());
        $personalhours = 0;
        foreach ( $report->getUnits() as $unit ) {
        	$unitDuration = strtotime($unit->getEndTime()) - strtotime($unit->getStartTime());
       		$personalhours += ($unitDuration * count($unit->getStaff()));
        }
        
        $filestring .= date($config ["formats"] ["date"], strtotime($report->getDate())) . $delimiter . 
        date($config ["formats"] ["time"], strtotime($report->getStartTime())) . $delimiter . 
            date($config ["formats"] ["time"], strtotime($report->getEndTime())) . $delimiter . 
            gmdate($config ["formats"] ["time"], $duration) . $delimiter . 
            gmdate($config ["formats"] ["time"], $personalhours) . $delimiter . 
            $report->getType()->getType() . $delimiter .
            $report->getTitle() . $delimiter . 
            "\n\nPersonal:\n";
                    
		foreach ( $report->getUnits() as $unit ) {
			$unitDuration = strtotime($unit->getEndTime()) - strtotime($unit->getStartTime());
			foreach ( $unit->getStaff() as $staff ) {
                $filestring .=  $staff->getName() . $delimiter .
                    $staff->getEngine()->getName() . $delimiter . 
                    gmdate($config ["formats"] ["time"], $unitDuration) .
                    "\n";
            }
        }
        $filestring .= "\n\n"; 
    }
    
    echo convertToWindowsCharset($filestring);
}

function reportsToInvoiceCSV($reports, $head = ""){
	global $config;
	$delimiter = ";";
	$filestring = $head;
	
	$filestring .= "Datum" . $delimiter .
	"Beginn" . $delimiter .
	"Ende" . $delimiter .
	"Titel" . $delimiter .
	"Personal" . $delimiter .
	"Dauer" . $delimiter .
	"Gesamtstunden" . $delimiter .
	"\n";
	
	foreach ( $reports as $report ) {
		
		$duration = strtotime($report->getEndTime()) - strtotime($report->getStartTime());
		$personalhours = 0;
		$personalcount = 0;
		foreach ( $report->getUnits() as $unit ) {
			$unitDuration = strtotime($unit->getEndTime()) - strtotime($unit->getStartTime());
			$personalhours += ($unitDuration * count($unit->getStaff()));
			$personalcount += count($unit->getStaff());
		}
		
		$filestring .= date($config ["formats"] ["date"], strtotime($report->getDate())) . $delimiter .
		date($config ["formats"] ["time"], strtotime($report->getStartTime())) . $delimiter .
		date($config ["formats"] ["time"], strtotime($report->getEndTime())) . $delimiter .
		$report->getTitle() . $delimiter .
		$personalcount . $delimiter .
		gmdate($config ["formats"] ["time"], $duration) . $delimiter .
		gmdate($config ["formats"] ["time"], $personalhours) . 
		"\n";
	}
	
	echo convertToWindowsCharset($filestring);
}

?>