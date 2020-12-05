<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

$eventtypes = get_eventtypes ();

// Pass variables (as an array) to template
$variables = array (
    'title' => "Export Wachberichte",
    'secured' => true,
    'eventtypes' => $eventtypes,
	'privilege' => EVENTMANAGER,
);

$type = -1;
$from = date('Y-m-01');
$to = date('Y-m-t');

if(userLoggedIn()){
    $user = $_SESSION ['intranet_userid'];
    $usersEngine = get_engine(get_engine_of_user($user));
        
    if($usersEngine->isadministration == true){
        $reports = get_reports("ASC");
    } else {
        $reports = get_filtered_reports($usersEngine->uuid, "ASC");
        $variables ['infoMessage'] = "Es werden nur Wachberichte angezeigt, die Ihrem Zug zugewiesen wurden";
    }
    
    if(isset($_POST['from'])){
        $type = $_POST['type'];
        $from = $_POST['from'];
        $to = $_POST['to'];

    }
    
    $reports = filter_reports($reports, $type, $from, $to);
    
    $variables ['type'] = $type;
    $variables ['from'] = $from;
    $variables ['to'] = $to;
    $variables ['reports'] = $reports;
   
}

if((isset($_POST['csv']) || isset($_POST['invoice'])) && current_user_has_privilege($variables ['privilege'])){
	
	header('Content-Encoding: UTF-8');
	header('Content-type: text/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename=Wachberichte_Export.csv');
	
    if($type == -1 ){
        $head = "Alle Wachen";
    } else {
        $head = "Wachen von Typ " . get_eventtype($type);
    }
    $head .= " zwischen " . 
        date($config ["formats"] ["date"], strtotime($from)) . " und " . 
        date($config ["formats"] ["date"], strtotime($to)) . "\n\n";
        
    if(isset($_POST['csv'])){
    	reportsToCSV($reports, $head);
    } else if(isset($_POST['invoice'])){
    	reportsToInvoiceCSV($reports, $head);
    }
    
    insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::ReportsExported, null));

    return;
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "reportExport_template.php", $variables );

function reportsToCSV($reports, $head = ""){
    global $config;
    $delimiter = ";";
    $filestring = $head;
        
    foreach ( $reports as $report ) {
        $row = get_report_object($report->uuid);
        
        $filestring .= "Datum" . $delimiter .
        "Beginn" . $delimiter .
        "Ende" . $delimiter .
        "Dauer" . $delimiter . 
        "Gesamtstunden" . $delimiter . 
        "Typ" . $delimiter .
        "Titel" . $delimiter .
        "\n";

        $duration = strtotime($row->end_time) - strtotime($row->start_time);
        $personalhours = 0;
        foreach ( $row->units as $entry ) {
            $unitDuration = strtotime($entry->end) - strtotime($entry->beginn);
            foreach ( $entry->staffList as $staff ) {
                $personalhours += $unitDuration;
            }
        }
        
        $filestring .= date($config ["formats"] ["date"], strtotime($row->date)) . $delimiter . 
            date($config ["formats"] ["time"], strtotime($row->start_time)) . $delimiter . 
            date($config ["formats"] ["time"], strtotime($row->end_time)) . $delimiter . 
            gmdate($config ["formats"] ["time"], $duration) . $delimiter . 
            gmdate($config ["formats"] ["time"], $personalhours) . $delimiter . 
            get_eventtype($row->type)->type . $delimiter .
            $row->title . $delimiter . 
            "\n\nPersonal:\n";
        
        foreach ( $row->units as $entry ) {
            $unitDuration = strtotime($entry->end) - strtotime($entry->beginn);
            foreach ( $entry->staffList as $staff ) {
                $filestring .=  $staff->name . $delimiter .
                    get_engine($staff->engine)->name . $delimiter . 
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
		$row = get_report_object($report->uuid);
		
		$duration = strtotime($row->end_time) - strtotime($row->start_time);
		$personalhours = 0;
		$personalcount = 0;
		foreach ( $row->units as $entry ) {
			$unitDuration = strtotime($entry->end) - strtotime($entry->beginn);
			foreach ( $entry->staffList as $staff ) {
				$personalhours += $unitDuration;
				$personalcount += 1;
			}
		}
		
		$filestring .= date($config ["formats"] ["date"], strtotime($row->date)) . $delimiter .
		date($config ["formats"] ["time"], strtotime($row->start_time)) . $delimiter .
		date($config ["formats"] ["time"], strtotime($row->end_time)) . $delimiter .
		$row->title . $delimiter .
		$personalcount . $delimiter .
		gmdate($config ["formats"] ["time"], $duration) . $delimiter .
		gmdate($config ["formats"] ["time"], $personalhours) . 
		"\n";
	}
	
	echo convertToWindowsCharset($filestring);
}

?>