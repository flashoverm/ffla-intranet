<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["hydrant"],
		'template' => "hydrantEdit_template.php",
		'title' => "Hydrant anlegen/bearbeiten",
	    'secured' => true,
	    'showFormular' => true,
	    'privilege' => Privilege::HYDRANTADMINISTRATOR
);
$variables = checkPermissions($variables);

$variables ['engines'] = $engineDAO->getEngines();

if(isset($_GET ['hydrant'])) {
    $variables ['title'] = 'Hydrant bearbeiten';
    
    $hy = $_GET ['hydrant'];
    $hydrant = $hydrantDAO->getHydrantByHy($hy);
    $variables ['hydrant'] = $hydrant;
    
} else {
    $variables ['title'] = 'Hydrant anlegen';
}

if(isset($_POST ['fid'])){
    
    if(isset($_POST ['hy'])){
        $hy = trim ( $_POST ['hy'] );
    }
    $fid = trim ( $_POST ['fid'] );
    $lat = floatval(trim ( $_POST ['lat'] ));
    $lng = floatval(trim ( $_POST ['lng'] ));
    $street = trim ( $_POST ['street'] );
    $district = trim ( $_POST ['district'] );
    $type = trim ( $_POST ['type'] );
    $engineUuid = trim ( $_POST ['engine'] );
    $engine = $engineDAO->getEngine($engineUuid);
    
    $checkbyff = false;
    $operating = false;
    
    if(isset($_POST ['checkbyff'])){
        $checkbyff = true;
    }
    if(isset($_POST ['operating'])){
        $operating = true;
    }
    
    
    if(isset($hydrant)){
        //update
    	$hydrant->setHydrantData($hy, $fid, $lat, $lng, $street, $district, $type, $engine, $checkbyff, $operating);
    	$hydrant = $hydrantDAO->save($hydrant);
    	
    	if($hydrant){
            $variables ['hydrant'] = $hydrant;
            $variables ['successMessage'] = "Hydrant aktualisiert";
            $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::HydrantUpdated, $hydrant->getUuid()));
        } else {
            $variables ['successMessage'] = "Hydrant konnte nicht aktualisiert werden";
        }
    } else {
        //insert
    	$fid_exists = $hydrantDAO->getHydrantByFid($fid);
    	$hy_exists = $hydrantDAO->getHydrantByHy($hy);
        
        if($fid_exists){
            if($hy_exists) {
                $variables ['alertMessage'] = "HY-Nummer und FID existieren bereits";
            } else {
                $variables ['alertMessage'] = "FID existiert bereits";
            } 
        } else if($hy_exists){
            $variables ['alertMessage'] = "HY-Nummer existiert bereits";
        } else {
        	//insert
        	$hydrant = new Hydrant();
        	$hydrant->setHydrantData($hy, $fid, $lat, $lng, $street, $district, $type, $engine, $checkbyff, $operating);
        	$hydrant = $hydrantDAO->save($hydrant);
        	
            if($hydrant){
                $variables ['hydrant'] = $hydrant;
                $variables ['successMessage'] = "Hydrant angelegt";
                $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::HydrantCreated, $hydrant->getUuid()));
            } else {
                $variables ['successMessage'] = "Hydrant konnte nicht angelegt werden";
            }
        }
    }    
}

renderLayoutWithContentFile ( $variables );
?>