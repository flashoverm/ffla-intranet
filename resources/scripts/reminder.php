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
        
        $dateManager = date_create($event->getDate());
        date_sub($dateManager, new DateInterval( "P".$config ["settings"] ["reminderAtDay"]."D" ));
        $dateParticipants = date_create($event->getDate());
        date_sub($dateParticipants, new DateInterval( "P".$config ["settings"] ["reminderAtDayParticipants"]."D" ));
        
        //echo $event->getUuid() . " - " . $date->format("d.m.Y") . "\n";
        
        if($dateManager->format("d.m.Y") == date("d.m.Y")){
            //Send reminder mail
            mail_not_full($event->getUuid());
            send_mail("guardian@thral.de", "Sending reminder - Event: " . $event->getUuid(), $date->format("d.m.Y") . " - " . date("d.m.Y"));
        }
        if($dateParticipants->format("d.m.Y") == date("d.m.Y")){
            //Send reminder mail
            mail_not_full_participants($event->getUuid());
            send_mail("guardian@thral.de", "Sending reminder participants - Event: " . $event->getUuid(), $date->format("d.m.Y") . " - " . date("d.m.Y"));
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
    