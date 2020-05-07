<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . "/db_mailing.php";

require_once "phpmailer/src/PHPMailer.php";
require_once "phpmailer/src/SMTP.php";
require_once "phpmailer/src/Exception.php";

function init_mail() {
    global $config;
    
   $mail = new PHPMailer ( true );
    $mail->isSMTP ();
    $mail->Host = $config ["mail"] ["host"];
    $mail->SMTPAuth = true;
    $mail->Username = $config ["mail"] ["username"];
    $mail->Password = $config ["mail"] ["password"];
    $mail->SMTPSecure = $config ["mail"] ["secure"];
    $mail->Port = $config ["mail"] ["port"];
    $mail->setFrom ( $config ["mail"] ["fromaddress"], $config ["mail"] ["fromname"] );
    $mail->CharSet = 'utf-8';
    //$mail->SMTPDebug = 2;
    return $mail;
}

function send_mail($to, $subject, $body, $attachment = NULL) {
    global $util;
    
    try{
        $mail = init_mail();
        
        $mail->addAddress ( $to );
        $mail->Subject = $subject;
        $mail->Body = $body . $util["footer"];
       
        if($attachment != NULL){
            $mail->AddAttachment($attachment, $name = basename($attachment),  $encoding = 'base64', $type = 'application/pdf');
        }
    
        if(!$mail->send ()){
            throw new Exception;
        }
    }catch(Exception $e){
        echo "<script language='javascript'>
				alert('Eine E-Mail konnte nicht gesendet werden');
			</script>";
        return false;
    }
    
    return true;
}


function send_mails($recipients, $subject, $body, $attachment = NULL) {
    $noError = true;
    foreach (filter_deactivated($recipients) as $to) {
        if(!send_mail($to->email, $subject, $body, $attachment)){
            $noError = false;
        }
    }
    return $noError;
}

function filter_deactivated($unfiltered){
    $filtered = array ();
    
    foreach ($unfiltered as $user) {
        #User with password (registered) and login enabled, or unregiered user
        if( ($user->loginenabled == 1 && isset($user->password) ) || !isset($user->password) ) {
            $filtered [] = $user;
        }
    }
    return $filtered;
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