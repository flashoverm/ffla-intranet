<?php 
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . "/mail_body.php";
require_once LIBRARY_PATH . "/mail.php";


function mail_send_inspection_report($report_uuid){
    global $config;
    global $bodies;
    
    $file = $config["paths"]["inspections"] . $report_uuid . ".pdf";
    
    $subject = "Hydranten-Prüfbericht";
    $body = $bodies["report_insert"];

    return send_mail_to_mailing(INSPECTIONREPORT, $subject, $body, $file);
}
?>