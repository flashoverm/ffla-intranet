<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_hydrant.php";
require_once LIBRARY_PATH . "/db_inspection.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/inspection_file_create.php";

require_once LIBRARY_PATH . "/class/HydrantInspection.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Prüfbericht erstellen",
    'secured' => true,
    'right' => ENGINEHYDRANTMANANGER
);

if(isset($_GET['inspection'])){
    
    $inspection = get_inspection($_GET['inspection']);
        
    if($inspection) {
        $variables['inspection'] = $inspection;
        $variables['title'] = "Prüfbericht bearbeiten";
    } else {
        $variables ['alertMessage'] = "Prüfbericht existiert nicht";
    }  
}

if(isset($_POST['maxidx'])){
    
    $date = $_POST['date'];
    $name = trim($_POST['name']);
    $vehicle = trim($_POST['vehicle']);
    $notes = trim($_POST['notes']);
    
    $max_idx = $_POST['maxidx'];
    
    if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1]).(0[1-9]|1[0-2]).[0-9]{4}$/", $date)) {
        //European date format -> change to yyyy-mm-dd
        $date = date_create_from_format('d.m.Y', $date)->format('Y-m-d');
    }
        
    $inspection = new HydrantInspection($date, $name, $vehicle, $notes);
    $inspection->engine = get_engine_of_user($_SESSION ['intranet_userid']);
    if(isset($_POST['uuid'])){
        $inspection->uuid = $_POST['uuid'];
    }
    
    for ($idx = 0; $idx <= $max_idx; $idx ++) {
                
        if(isset($_POST['h' . $idx . 'hy'])){
                        
            $hy = $_POST['h' . $idx . 'hy'];
            $type = $_POST['h' . $idx . 'type'];
            
            $hydrant = new Hydrant($hy, $idx, $type);
            
            for($c_idx = 0; $c_idx < sizeof($hydrant_criteria); $c_idx ++){
                
                if(isset($_POST['h' . $idx . 'c' . $c_idx])){
                    $hydrant->addCriterion(new Criterion($c_idx, true));
                } else {
                    $hydrant->addCriterion(new Criterion($c_idx, false));
                }                
            }
                
            $inspection->addHydrant($hydrant);
        }
    }
        
    if(isset($_POST['assistant'])){
        $variables['infoMessage'] = "Ihre Eingaben wurden noch nicht gespeichert!";
        $variables['inspection'] = $inspection;
    } else {
        
        $hydrantsNotFound = "";
        $hydrantsCheckBySW = "";
        
        foreach($inspection->hydrants as $hydrant){
            $hydrant_db = get_hydrant($hydrant->hy);
            if($hydrant_db){
                $hydrant->uuid = $hydrant_db->uuid;
                if(!$hydrant_db->checkbyff){
                    $hydrantsCheckBySW = $hydrantsCheckBySW . $hydrant->hy . ", ";
                }
            } else {
                $hydrantsNotFound = $hydrantsNotFound . $hydrant->hy . ", ";
            }
        }
               
        if($hydrantsNotFound == "" && $hydrantsCheckBySW == ""){
       
            if($inspection->uuid != ""){
                
                if(update_inspection($inspection)){
                    createInspectionFile($inspection->uuid);
                    if(mail_send_inspection_report_update($inspection->uuid)){
                        $variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
                    }
                    $variables ['successMessage'] = "Prüfbericht aktualisiert";
                    header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/inspection/". $inspection->uuid ); // redirects
                } else {
                    $variables ['alertMessage'] = "Prüfbericht konnte nicht aktualisiert werden";
                    $variables['inspection'] = $inspection;
                }
            } else {
                $inspection_uuid = insert_inspection($inspection);
                if($inspection_uuid){
                    createInspectionFile($inspection->uuid);
                    if(mail_send_inspection_report($inspection->uuid)){
                        $variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
                    }
                    $variables ['successMessage'] = "Prüfbericht gespeichert";
   					header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/inspection/". $inspection->uuid ); // redirects
                    
                } else {
                    $variables ['alertMessage'] = "Prüfbericht konnte nicht gespeichert werden";
                    $variables['inspection'] = $inspection;
                }
            }
        } else {
            $alertMessage = "";
            if($hydrantsNotFound != ""){
                $alertMessage .= "HY-Nummer(n) " . substr_replace($hydrantsNotFound , "", -2) . " existieren nicht";
            }
            if($hydrantsCheckBySW != ""){
                if($alertMessage != ""){
                    $alertMessage .= "<br>";
                }
                $alertMessage .= "HY-Nummer(n) " . substr_replace($hydrantsCheckBySW, "", -2) . " werden von den Stadtwerken Landshut geprüft";
            }
            $variables ['alertMessage'] = $alertMessage;
            $variables['inspection'] = $inspection;
        }
    }
}

$variables['criteria'] = $hydrant_criteria;

renderLayoutWithContentFile($config["apps"]["hydrant"], "inspectionEdit_template.php", $variables);
