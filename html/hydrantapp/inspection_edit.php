<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/file_create.php";

require_once LIBRARY_PATH . "/class/HydrantInspection.php";
require_once LIBRARY_PATH . "/class/constants/HydrantCriteria.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Prüfbericht erstellen",
    'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER
);

$user_engine = $userController->getCurrentUser()->getEngine()->getUuid();

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
    $inspection->engine = $user_engine;
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
        $hydrantsCheckByOther = "";
        
        foreach($inspection->hydrants as $hydrant){
            $hydrant_db = get_hydrant($hydrant->hy);
            if($hydrant_db){
                $hydrant->uuid = $hydrant_db->uuid;
                if( (! $hydrant_db->checkbyff ) || ( $hydrant_db->engine != $user_engine ) ){
                	$hydrantsCheckByOther = $hydrantsCheckByOther . $hydrant->hy . ", ";
                }
            } else {
                $hydrantsNotFound = $hydrantsNotFound . $hydrant->hy . ", ";
            }
        }
               
        if($hydrantsNotFound == "" && $hydrantsCheckByOther == ""){
       
            if($inspection->uuid != ""){
                
                if(update_inspection($inspection)){
                    createInspectionFile($inspection->uuid);
                    if(mail_send_inspection_report_update($inspection->uuid)){
                        $variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
                    }
                    $variables ['successMessage'] = "Prüfbericht aktualisiert";
                    $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::InspectionUpdated, $inspection->uuid));
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
                    $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::InspectionCreated, $inspection->uuid));
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
            if($hydrantsCheckByOther != ""){
                if($alertMessage != ""){
                    $alertMessage .= "<br>";
                }
                $alertMessage .= "HY-Nummer(n) " . substr_replace($hydrantsCheckByOther, "", -2) . " werden von den Stadtwerken Landshut oder anderen Zügen geprüft";
            }
            $variables ['alertMessage'] = $alertMessage;
            $variables['inspection'] = $inspection;
        }
    }
}

$variables['criteria'] = $hydrant_criteria;

renderLayoutWithContentFile($config["apps"]["hydrant"], "inspectionEdit_template.php", $variables);
