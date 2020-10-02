<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . "/db_mailing.php";
require_once LIBRARY_PATH . "/db_maillog.php";
require_once LIBRARY_PATH . "/mail_body.php";
require_once LIBRARY_PATH . "/class/constants/MaillogStates.php";

require_once "phpmailer/src/PHPMailer.php";
require_once "phpmailer/src/SMTP.php";
require_once "phpmailer/src/Exception.php";

function init_mail() {
    global $config;
    
	$mail = new PHPMailer ( true );
    $mail->isSMTP ();
    $mail->Host = $config ["mail"] ["host"];
    $mail->SMTPAuth = true;
    if(isset($config ["mail"] ["username"])){
    	$mail->Username = $config ["mail"] ["username"];
    	$mail->Password = $config ["mail"] ["password"];
    	$mail->SMTPSecure = $config ["mail"] ["secure"];
    	$mail->Port = $config ["mail"] ["port"];
    }
    $mail->setFrom ( $config ["mail"] ["fromaddress"], $config ["mail"] ["fromname"] );
    $mail->CharSet = 'utf-8';
    //$mail->SMTPDebug = 2;
    return $mail;
}

function send_mail($to, $subject, $body, $attachment = NULL) {
	global $util, $config;
    
    if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
	    try{
	        $mail = init_mail();
	        
	        $mailState = NULL;
	        
	        $mail->addAddress ( $to );
	        $mail->Subject = $subject;
	        
	        $mailBody = $body . $util["footer"];
	       
	        if($attachment != NULL){
	        	if(file_exists ( $attachment ) ){
	        		$mail->AddAttachment($attachment, $name = basename($attachment),  $encoding = 'base64', $type = 'application/pdf');
	        	} else {
	        		$mailBody = $mailBody . $util["attachment_error"];
	        		$mailState = MaillogStates::AttachmentError;
	        	}
	        }
	        
	        $mail->Body = $mailBody;
	    
	        if( ! $config ["settings"] ["deactivateOutgoingMails"] ) {
	        	if( ! $mail->send ()){
	        		throw new Exception;
	        	}
	        } else {
	        	if( $mailState == NULL){
	        		$mailState = MaillogStates::Deactivated;
	        	}
	        }

	        if( $mailState == NULL){
	        	$mailState = MaillogStates::Sent;
	        }
	        insert_maillog($to, $subject, $mailState, $mailBody);
	        return true;
	        
	    }catch(Exception $e){
	    	if( startsWith($e->getMessage(), "SMTP connect() failed") ){
	    		insert_maillog($to, $subject, MaillogStates::MailConnectError, $mailBody, $e->getMessage());
	    	} else {
	    		insert_maillog($to, $subject, MaillogStates::Failed, $mailBody, $e->getMessage());
	    	}
	        echo "<script language='javascript'>
					alert('Eine E-Mail konnte nicht gesendet werden');
				</script>";
	    	return false;
	    }
    }
    insert_maillog($to, $subject, MaillogStates::Failed, $mailBody, "Invalid Mail Adress");
    return false;
}


function send_mails($recipients, $subject, $body, $attachment = NULL) {
    $noError = true;
    foreach (removeLockedUsers($recipients) as $to) {
        if(!send_mail($to->email, $subject, $body, $attachment)){
            $noError = false;
        }
    }
    return $noError;
}


function send_mail_to_mailing($mailing, $subject, $body, $attachment = NULL){
    $recipients = get_member($mailing);
    
    $noError = true;
    foreach($recipients as $recipient){
        if(!send_mail($recipient->email, $subject, $body, $attachment)){
            $noError = false;    
        }
    }
    return $noError;
}

function removeLockedUsers($recipients){
	$filtered = array ();
	
	foreach ($recipients as $user) {
		#User with password (registered) and login enabled, or unregiered user
		if( ($user->locked == 0 && isset($user->password) ) || !isset($user->password) ) {
			$filtered [] = $user;
		}
	}
	
	return $filtered;
}
