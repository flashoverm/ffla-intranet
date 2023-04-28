<?php 

require_once LIBRARY_PATH . "/mail/MailBase.php";


function mail_add_user($email, $password) {
    global $userDAO;
    $subject = "Benutzer angelegt";
    
    $user = $userDAO->getUserByEmail($email);
    
    $parameter = array(
        'email' => $email,
        'password' => $password
    );
    
    return sendMailWithTemplate ( $user, $subject, TEMPLATES_PATH . "/usersapp/mails/addUser_mail.php", $parameter);
}

function mail_reset_password($user_uuid, $password) {
    global $userDAO;
    $subject = "Passwort zurückgesetzt";
    
    $user = $userDAO->getUserByUUID($user_uuid);
    
    $parameter = array(
        'password' => $password
    );
    
    return sendMailWithTemplate ( $user, $subject, TEMPLATES_PATH . "/usersapp/mails/resetPassword_mail.php", $parameter);
    
}

function mail_forgot_password(String $email, String $token) {
    global $config, $userDAO;
    $subject = "Passwort zurücksetzen";
    
    $user = $userDAO->getUserByEmail($email);
    
    $parameter = array(
        'reset_link' => $config ["urls"] ["base_url"] . "/users/password/reset/" . $token
    );
    
    return sendMailWithTemplate ( $user, $subject, TEMPLATES_PATH . "/usersapp/mails/forgotPassword_mail.php", $parameter);
    
}

