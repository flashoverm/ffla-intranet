<?php
/**
 *	Create crontab entry:
 *  0 1 * * * /usr/bin/php /var/www/ffla-intranet/resources/scripts/reminder.php
 *
 */

require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );
require_once LIBRARY_PATH . "/mail_controller.php";

$events = $eventDAO->getActiveEvents();

foreach ( $events as $event ) {
       
	if(! $event->isEventFull() ){
        
        $date = date_create($event->getDate());
        date_sub($date, new DateInterval( "P".$config ["settings"] ["reminderAtDay"]."D" ));
                
        if($date->format("d.m.Y") == date("d.m.Y")){
            //Send reminder mail
            mail_not_full($event->getUuid());
            send_mail("guardian@thral.de", "Sending reminder - Event: " . $event->getUuid(), $date->format("d.m.Y") . " - " . date("d.m.Y"));
        }
    }
}
    