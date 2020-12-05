<?php
require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/db_event.php";

$events = get_all_active_events();

foreach ( $events as $event ) {
       
    if(!is_event_full($event->uuid)){
        
        $date = date_create($event->date);
        date_sub($date, new DateInterval( "P".$config ["settings"] ["reminderAtDay"]."D" ));
                
        if($date->format("d.m.Y") == date("d.m.Y")){
            //Send reminder mail
            mail_not_full($event->uuid);
            send_mail("guardian@thral.de", "Sending reminder - Event: " . $event->uuid, $date->format("d.m.Y") . " - " . date("d.m.Y"));
        }
    }
}
    