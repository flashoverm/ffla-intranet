<?php
/**
 *	Create crontab entry:
 *  0 1 * * * /usr/bin/php /var/www/ffla-intranet/resources/scripts/reminder.php
 *
 */

require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );
require_once LIBRARY_PATH . "/mail_controller.php";

$activeEvents = $eventDAO->getActiveEvents($_GET);

foreach ( $activeEvents as $event ) {
          
	if(! $event->isEventFull() ){
        
        $date = date_create($event->getDate());
        date_sub($date, new DateInterval( "P".$config ["settings"] ["reminderAtDay"]."D" ));
                
        //echo $event->getUuid() . " - " . $date->format("d.m.Y") . "\n";
        
        if($date->format("d.m.Y") == date("d.m.Y")){
            //Send reminder mail
            mail_not_full($event->getUuid());
            send_mail("guardian@thral.de", "Sending reminder - Event: " . $event->getUuid(), $date->format("d.m.Y") . " - " . date("d.m.Y"));
        }
    }
}

$unapprovedReports = $reportDAO->getUnapprovedReports($_GET);

foreach ( $unapprovedReports as $report ) {
    
    $date = date_create($report->getDate());
    date_add($date, new DateInterval( "P".$config ["settings"] ["reportReminderAfterDays"]."D" ));
    $dateLate = date_create($report->getDate());
    date_add($dateLate, new DateInterval( "P".($config ["settings"] ["reportReminderAfterDays"]*2)."D" ));
    
    //echo $report->getUuid() . " - " . $date->format("d.m.Y") . " - " . $report->getDate() . "\n";
    
    if($date->format("d.m.Y") == date("d.m.Y") || $dateLate->format("d.m.Y") == date("d.m.Y")){
        //Send reminder mail
        mail_not_approved($report->getUuid());
        send_mail("guardian@thral.de", "Sending reminder - Report: " . $report->getUuid(), $date->format("d.m.Y") . " - " . date("d.m.Y"));
    }
    
}
    