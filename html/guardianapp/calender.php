<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

if (isset($_GET['id'])) {

    $variables = array(
        'title' => 'Download Kalenderdatei',
        'secured' => false
    );
    
    $uuid = trim($_GET['id']);
    $event = $eventDAO->getEvent($uuid);
    $eol = "\r\n";
    
    // iCal date format: yyyymmddThhiissZ
    // PHP equiv format: Ymd\This
    // The Function
    function dateToCal($date, $time)
    {
        $data =  date_create_from_format('Y-m-d H:i:s', $date . " " . $time);            
        return date_format($data, 'Ymd\This') . 'Z';
    }
    
    function getEndDateTime($date, $end_time, $start_time){
    	if(isset($end_time)){
    		return dateToCal($date, $end_time);
    	}
    	$approx_end = date( "H:i:s", strtotime( $start_time ) + 4 * 3600 );
    	return dateToCal($date, $approx_end);
    }
    
    function createICS(Event $event){
    	global $config, $eol;
    	$type = $event->getType()->getType();
    	
    	header('Content-Disposition: attachment; filename=event.ics');
    	
    	$ical = 'BEGIN:VCALENDAR' . $eol .
    	'VERSION:2.0' . $eol .
    	'PRODID:GuardianByFFLandshutDE' . $eol .
    	'CALSCALE:GREGORIAN' . $eol .
    	'METHOD:REQUEST' . $eol . 
    	'BEGIN:VEVENT' . $eol .
    	'STATUS:CONFIRMED' . $eol .
    	'UID:wachverwaltung@feuerwehr-landshut.de' . $eol .
    	'LOCATION:' . html_entity_decode($type) . $eol .
    	'DESCRIPTION:' . html_entity_decode("Weitere Infos unter " . $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/" . $event->getUuid() . 
    			" sowie der vorausgefüllte Wachbericht unter " . $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/" . $event->getUuid()) . $eol . 
    	'URL;VALUE=URI:' . $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/".$event->getUuid() . $eol .
    	'SUMMARY:' . html_entity_decode($type . " " . $event->getTitle()) . $eol .
    	'DTSTART:' . dateToCal($event->date, $event->getStartTime()) . $eol .
    	'DTEND:' . getEndDateTime($event->date, $event->getEndTime(), $event->getStartTime()) . $eol .
    	'DTSTAMP:' . date_format(date_create("now"), 'Ymd\This')  . 'Z' . $eol .
    	'END:VEVENT' . $eol .
    	'END:VCALENDAR';
    	
    	return $ical;    	
    }
    
    function createVCS(Event $event){
    	global $config, $eol;
    	$type = $event>getType()->getType();
    	
    	header('Content-Disposition: attachment; filename=event.vcs');
    	
    	$vcs='BEGIN:VCALENDAR' . $eol .
    		'BEGIN:VEVENT' . $eol .
    		'DTSTART:' . dateToCal($event->getDate(), $event->getStartTime()) . $eol .
    		'DTEND:' . getEndDateTime($event->getDate(), $event->getEndTime(), $event->getStartTime()) . $eol .
    		'LOCATION;ENCODING=QUOTED-PRINTABLE:' . html_entity_decode($type) . $eol .
    		'DESCRIPTION:' . html_entity_decode("Weitere Infos unter " . $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/" . $event->getUuid()
    				. " sowie der vorausgefüllte Wachbericht unter " . $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/" . $event->getUuid()) . $eol .
    		'SUMMARY;ENCODING=QUOTED-PRINTABLE:' . html_entity_decode($type . " " . $event->getTitle()) . $eol .
			'PRIORITY:' . '3' . $eol . 
			'END:VEVENT' . $eol .
			'END:VCALENDAR';
    	
    	return $vcs;
    }

    header('Content-type: text/calendar; charset=utf-8');
    
    //echo createICS($event);
    
    echo createVCS($event);
}

?>