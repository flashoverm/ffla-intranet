<?php 

require_once LIBRARY_PATH . "/mail/MailBase.php";


function mail_send_inspection_report($report_uuid){
    global $config;
    global $bodies;
    
    $file = $config["paths"]["inspections"] . $report_uuid . ".pdf";
    
    $subject = "Hydranten-Prüfbericht";
    $body = $bodies["report_insert"] . get_inspection_link($report_uuid);
    
    return send_mail_to_mailinglist(INSPECTIONREPORT, $subject, $body, $file);
}

function mail_send_inspection_report_update($report_uuid){
    global $config;
    global $bodies;
    
    $file = $config["paths"]["inspections"] . $report_uuid . ".pdf";
    
    $subject = "Hydranten-Prüfbericht aktualisiert";
    $body = $bodies["report_update"] . get_inspection_link($report_uuid);
    
    return send_mail_to_mailinglist(INSPECTIONREPORT, $subject, $body, $file);
}

function get_inspection_link($inspection_uuid){
    global $config;
    return $config ["urls"] ["base_url"] . $config ["urls"] ["hydrantapp_home"] . "/inspection/view/" . $inspection_uuid;
}
