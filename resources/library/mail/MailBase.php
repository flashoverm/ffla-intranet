<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once LIBRARY_PATH . "/mail/MailText.php";

/*
 * Mail sending
 */

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

function send_mail($to, $subject, $body, $attachments = null, $footer = true, $sendAsync = true) {
    global $util, $config, $mailLogDAO;
    
    $mailBody = $body;
    if( $footer ){
        $mailBody = $mailBody . $util["footer"];
    }
    
    $mailState = null;
    
    if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
        try{
            $mail = init_mail();
            
            $mail->addAddress ( $to );
            $mail->Subject = $subject;
            
            if($attachments != null){
                
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
                
                if($attachments == null && $sendAsync){
                    if(send_mail_async($to, $subject, $mailBody)){
                        return true;
                    }
                    $mailLog = MailLog::fromMail($to, $subject, MailLog::CouldNotAddToQueue, $mailBody);
                    $mailLogDAO->save($mailLog);
                    return false;
                }
                
                if( ! $mail->send ()){
                    throw new Exception;
                }

            } else {
                if( $mailState == null){
                    $mailState = MailLog::Deactivated;
                }
            }
            
            if( $mailState == null){
                $mailState = MailLog::Sent;
            }
            $mailLog = MailLog::fromMail($to, $subject, $mailState, $mailBody);
            $mailLogDAO->save($mailLog);
            return true;
            
        }catch(Exception $e){
            if( StringUtil::startsWith($e->getMessage(), "SMTP connect() failed") ){
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

function send_mail_async($to, $subject, $body){
    global $mailQueueDAO;
    
    $mail = new MailQueueElement();
    return $mailQueueDAO->save($mail->fromMail($to, $subject, $body));
}

function send_mail_to_mailinglist($mailing, $subject, $body, $attachment = null){
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

function sendMailWithTemplate($recipient, $subject, $template, array $parameter, $attachments = null, $footer = true){
    
    //Do not send if user is locked
    if(
        ( ! $recipient->getLocked() && $recipient->getPassword() != null )
        ||
        $recipient->getPassword() == null
        ) {
            
            $body = renderMail($recipient, $template, $parameter);
            return send_mail($recipient->getEmail(), $subject, $body, $attachments, $footer);
        }
        return true;
}

function sendMailsWithTemplate($recipients, $subject, $template, array $parameter, $attachment = null) {
    if( ! is_array($recipients)){
        return sendMailWithTemplate($recipients, $subject, $template, $parameter, $attachment);
    }

    $success = true;
    foreach ($recipients as $to) {
        if(!sendMailWithTemplate($to, $subject, $template, $parameter, $attachment)){
            $success = false;
        }
    }
    return $success;
}

function sendMailsWithBody($recipients, $subject, $body, $attachment = null) {
    $success = true;
    foreach ($recipients as $to) {
        if(
            ( ! $to->getLocked() && $to->getPassword() != null )
            ||
            $to->getPassword() == null
            ) {
                if(!send_mail($to->getEmail(), $subject, $body, $attachment)){
                    $success = false;
                }
            }
    }
    return $success;
}

/*
 * Mail rendering
*/


function renderMail($recipient, $template, array $parameter){
    
    $parameter = addDefaultMailParameters($parameter);
    
    $content = render_php($template, $parameter);
    
    return renderMailHead($recipient) . $content;
}

function renderMailHead($recipient){
    $head = "Hallo";
    
    if(isset($recipient)){
        $head .= " " . $recipient->getFirstname();
    }
    
    $head .= ",\n\n";
    
    return $head;
}

function addDefaultMailParameters(array $parameter){
    global $config;
    
    $parameter['portal_url'] = $config ["urls"] ["base_url"];
    $parameter['config'] = $config;
    
    return $parameter;
}

function render_php($path, array $variables = array()){
    ob_start();
    
    if (count ( $variables ) > 0) {
        foreach ( $variables as $key => $value ) {
            if (strlen ( $key ) > 0) {
                ${$key} = $value;
            }
        }
    }
    
    include($path);
    $var=ob_get_contents();
    ob_end_clean();
    return $var;
}
