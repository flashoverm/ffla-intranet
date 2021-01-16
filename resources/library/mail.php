<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once LIBRARY_PATH . "/mail_body.php";

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

function send_mail($to, $subject, $body, $attachments = NULL, $footer = true) {
	global $util, $config, $mailLogDAO;
    
	$mailBody = $body;
	if( $footer ){
		$mailBody = $mailBody . $util["footer"];
	}
	
	$mailState = NULL;
	
    if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
	    try{
	        $mail = init_mail();
	        
	        $mail->addAddress ( $to );
	        $mail->Subject = $subject;
	       
	        if($attachments != NULL){

	        	if( ! is_array($attachments)){
	        		$attachment = $attachments;
	        		$attachments = array($attachment);
	        	}
	        	foreach($attachments as $attachment){
	        		if(file_exists ( $attachment ) ){
	        			$mail->AddAttachment($attachment, basename($attachment), 'base64', 'application/pdf');
	        		} else {
	        			$mailBody = $mailBody . $util["attachment_error"];
	        			$mailState = MailLog::AttachmentError;
	        		}
	        	}
	        }
	        
	        $mail->Body = $mailBody;
	    
	        if( ! $config ["settings"] ["deactivateOutgoingMails"] ) {
	        	if( ! $mail->send ()){
	        		throw new Exception;
	        	}
	        } else {
	        	if( $mailState == NULL){
	        		$mailState = MailLog::Deactivated;
	        	}
	        }

	        if( $mailState == NULL){
	        	$mailState = MailLog::Sent;
	        }
	        $mailLog = MailLog::fromMail($to, $subject, $mailState, $mailBody);
	        $mailLogDAO->save($mailLog);
	        return true;
	        
	    }catch(Exception $e){
	    	if( startsWith($e->getMessage(), "SMTP connect() failed") ){
	    		$mailState = MailLog::MailConnectError;
	    	} else {
	    		$mailState = MailLog::Failed;
	    	}
	    	$mailLog = MailLog::fromMail($to, $subject, $mailState, $mailBody, $e->getMessage());
	    	$mailLogDAO->save($mailLog);
	    	
	        echo "<script language='javascript'>
					alert('Eine E-Mail konnte nicht gesendet werden');
				</script>";
	    	return false;
	    }
    }
    $mailLog = MailLog::fromMail($to, $subject, MailLog::Failed, $mailBody);
    $mailLogDAO->save($mailLog);
    return false;
}


function send_mails($recipients, $subject, $body, $attachment = NULL) {
    $success = true;
    foreach (removeLockedUsers($recipients) as $to) {
        if(!send_mail($to->getEmail(), $subject, $body, $attachment)){
        	$success = false;
        }
    }
    return $success;
}


function send_mail_to_mailinglist($mailing, $subject, $body, $attachment = NULL){
	global $mailingList;
	
	$recipients = $mailingList[$mailing];
    
	$success = true;
    foreach($recipients as $recipient){
        if(!send_mail($recipient, $subject, $body, $attachment)){
        	$success = false;    
        }
    }
    return $success;
}

function removeLockedUsers($recipients){
	$filtered = array ();
	
	foreach ($recipients as $user) {
		#User with password (registered) and login enabled, or unregiered user
		if( 
				( ! $user->getLocked() && $user->getPassword() != null )
				|| $user->getPassword() == null ) {
			$filtered [] = $user;
		}
	}
	
	return $filtered;
}
