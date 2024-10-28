<?php

require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );
require_once LIBRARY_PATH . "/mail_controller.php";

while (1){
       
    $mail = $mailQueueDAO->getOldestEntry();
    
    if($mail) {
        //Send mail in queue
        $result = send_mail($mail->getRecipient(), $mail->getSubject(), $mail->getBody(), NULL, false, false);
        
        if($result){
            //If mail is send, delete from queue
            $mailQueueDAO->delete($mail);
        } else {
            
            //If mail could not be sent, put it at the end of the queue and try again
            //But only limited retries
            if($mail->getRetries() < 3){
                $mailQueueDAO->retry($mail);
            } else {
                $mailQueueDAO->delete($mail);
            }
        }
        
        //Wait a short period before sending the next mail of the queue to give the mailserver time to cool down
        echo "sleep - cool down for next mail";
        sleep(2);
    } else {
        //No mails in queue, wait a longer period to prevent to much db requests
        echo "sleep - wait for new mails";
        sleep(30);
    }
    
}
