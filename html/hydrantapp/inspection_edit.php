<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "inspectionEdit_template.php",
	    'title' => "Prüfbericht erstellen",
	    'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER,
		'criteria' => InspectedHydrant::HYDRANTCRITERIA,
);
$variables = checkPermissions($variables);

if(isset($_GET['inspection'])){
    
    $inspection = $inspectionDAO->getInspection($_GET['inspection']);
        
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
	
	if(isset($_GET['inspection'])){
		$inspection = $variables['inspection'];
		$inspection->clearInspectedHydrants();
	} else {
		$inspection = new Inspection();
		$inspection->setEngine($userController->getCurrentUser()->getEngine());
	}
	$inspection->setInspectionData($date, $name, $vehicle, $notes);
	
	$hydrantsNotFound = "";
	$hydrantsCheckByOther = "";
	$hydrantDuplicates = "";
	$hys = array();
	
	for ($idx = 0; $idx <= $max_idx; $idx ++) {
		
		if(isset($_POST['h' . $idx . 'hy'])){

			$hy = $_POST['h' . $idx . 'hy'];
			
			//Check for duplicate hy numbers
			if(in_array ($hy, $hys) ){
				$hydrantDuplicates = $hydrantDuplicates . $hy . ", ";
			}
			$hys[] = $hy;
			
			$hydrant = $hydrantDAO->getHydrantByHy($hy);
			$userEngine = $userController->getCurrentUser()->getEngine();
			
			//Check if hydrant is existing (first if) or hydrant is checked by others (second if)
			if(! $hydrant){
				$hydrant = new Hydrant();
				$hydrant->setHy($hy);
				$hydrantsNotFound = $hydrantsNotFound . $hydrant->getHy() . ", ";
			} else if( (! $hydrant->getCheckByFF() ) || ( $hydrant->getEngine()->getUuid() != $userEngine->getUuid() )  ){
				$hydrantsCheckByOther = $hydrantsCheckByOther . $hydrant->getHy() . ", ";
			}
				
			$type = $_POST['h' . $idx . 'type'];
			
			$inspected = new InspectedHydrant();
			$inspected->setIndex($idx);
			$inspected->setType($type);
			$inspected->setHydrant($hydrant);
			
			for($c_idx = 0; $c_idx < sizeof(InspectedHydrant::HYDRANTCRITERIA); $c_idx ++){
				
				if(isset($_POST['h' . $idx . 'c' . $c_idx])){
					$inspected->addCriterion($idx, $c_idx, true);
				} else {
					$inspected->addCriterion($idx, $c_idx, false);
				}
			}
			
			$inspection->addInspectedHydrant($inspected);
		}
	}
  
    if(isset($_POST['assistant'])){
        $variables['infoMessage'] = "Ihre Eingaben wurden noch nicht gespeichert!";
        $variables['inspection'] = $inspection;
    } else {
		if($hydrantsNotFound == "" && $hydrantsCheckByOther == "" && $hydrantDuplicates == ""){
    		$inspection = $inspectionDAO->save($inspection);
    		if($inspection){
    			createInspectionFile($inspection->getUuid());
    			
    			if(isset($_GET['inspection'])){
    				//updated inspection
    				if(mail_send_inspection_report_update($inspection->getUuid())){
    					$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
    				}
    				$variables ['successMessage'] = "Prüfbericht aktualisiert";
    				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::InspectionUpdated, $inspection->getUuid()));
    				header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/inspection/". $inspection->getUuid() );  // redirects
    			} else {
    				//inserted inspection
    				if(mail_send_inspection_report($inspection->getUuid())){
    					$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
    				}
    				$variables ['successMessage'] = "Prüfbericht gespeichert";
    				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::InspectionCreated, $inspection->getUuid()));
    				header ( "Location: " . $config["urls"]["hydrantapp_home"] . "/inspection/". $inspection->getUuid() ); // redirects
    			}
    			
    		} else {
    			if(isset($_GET['inspection'])){
    				$variables ['alertMessage'] = "Prüfbericht konnte nicht aktualisiert werden";
    				$variables['inspection'] = $inspection;
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
            if($hydrantDuplicates != ""){
            	if($alertMessage != ""){
            		$alertMessage .= "<br>";
            	}
            	$alertMessage .= "HY-Nummer(n) " . substr_replace($hydrantDuplicates, "", -2) . " sind mehrfach im Prüfbericht enthalten";
            }
            $variables ['alertMessage'] = $alertMessage;
            $variables['inspection'] = $inspection;
        }
    }
}

renderLayoutWithContentFile($variables);
