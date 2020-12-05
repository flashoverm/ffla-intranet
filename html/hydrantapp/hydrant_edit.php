<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_hydrant.php";

// Pass variables (as an array) to template
$variables = array (
    'secured' => true,
    'showFormular' => true,
    'privilege' => HYDRANTADMINISTRATOR
);

$variables ['engines'] = get_engines();

if(isset($_GET ['hydrant'])) {
    $variables ['title'] = 'Hydrant bearbeiten';
    
    $hy = $_GET ['hydrant'];
    $hydrant = get_hydrant($hy);
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
    $engine = trim ( $_POST ['engine'] );
    
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
        $uuid = update_hydrant($hydrant->uuid, $hy, $fid, $lat, $lng, $street, $district, $type, $engine, $checkbyff, $operating);
        if($uuid){
            $hydrant = get_hydrant_by_uuid($uuid);
            $variables ['hydrant'] = $hydrant;
            $variables ['successMessage'] = "Hydrant aktualisiert";
            insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::HydrantUpdated, $uuid));
        } else {
            $variables ['successMessage'] = "Hydrant konnte nicht aktualisiert werden";
        }
    } else {
        //insert
        $fid_exists = get_hydrant_by_fid($fid);
        $hy_exists = get_hydrant($hy);
        
        if($fid_exists){
            if($hy_exists) {
                $variables ['alertMessage'] = "HY-Nummer und FID existieren bereits";
            } else {
                $variables ['alertMessage'] = "FID existiert bereits";
            } 
        } else if($hy_exists){
            $variables ['alertMessage'] = "HY-Nummer existiert bereits";
        } else {
            
            $uuid = insert_hydrant($hy, $fid, $lat, $lng, $street, $district, $type, $engine, $checkbyff, $operating);
            if($uuid){
                $hydrant = get_hydrant_by_uuid($uuid);
                $variables ['hydrant'] = $hydrant;
                $variables ['successMessage'] = "Hydrant angelegt";
                insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::HydrantCreated, $uuid));
            } else {
                $variables ['successMessage'] = "Hydrant konnte nicht angelegt werden";
            }
        }
    }    
}

renderLayoutWithContentFile ($config["apps"]["hydrant"], "hydrantEdit_template.php", $variables );
?>