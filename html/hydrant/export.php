<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
    'app' => $config["apps"]["hydrant"],
    'template' => "hydrantExport_template.php",
    'title' => "Export Hydranten",
    'secured' => true,
    'privilege' => Privilege::ENGINEHYDRANTMANANGER
);
checkSitePermissions($variables);

$engine = $userController->getCurrentUser()->getEngine();

$_GET[ResultSet::SHOWALL_PARAM] = true;

if($userController->hasCurrentUserPrivilege(Privilege::HYDRANTADMINISTRATOR)){
    $hydrants = $hydrantDAO->getHydrants($_GET);
} else {
    $hydrants =  $hydrantDAO->getHydrantsOfEngine($engine->getUuid(), $_GET);
}

if((isset($_POST['csv'])) && $userController->hasCurrentUserPrivilege($variables ['privilege'])){
    
    $filename = "Hydranten_Export.csv";
    header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    $head = "Übersicht Hydranten\n";
        
    $delimiter = ";";
    $filestring = $head;
    
    $filestring .= "HY" . $delimiter .
        "FID" . $delimiter .
        "Straße" . $delimiter .
        "Stadtteil" . $delimiter .
        "Löschzug" . $delimiter .
        "Typ" . $delimiter .
        "Geprüft am" . $delimiter .
        "Koordinaten" . $delimiter .
        "Prüfung durch Stadtwerke" . $delimiter .
        "\n";    
    
    foreach ( $hydrants as $data ) {
        
        $checkString = "";
        if($data->getLastCheck() != null){
            $checkString = date($config ["formats"] ["date"], strtotime($data->getLastCheck() ?? ''));
        }
        
        $filestring .= $data->getHy() . $delimiter .
        $data->getFid() . $delimiter .
        $data->getStreet() . $delimiter .
        $data->getDistrict() . $delimiter .
        $data->getEngine()->getName() . $delimiter .
        $data->getType() . $delimiter .
        $checkString . $delimiter .
        $data->getLat() . "," . $data->getLng() . $delimiter .
        !$data->getCheckByFF() . $delimiter .
        "\n"; 
    }
    
    echo StringUtil::convertToWindowsCharset($filestring);
    
    $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::HydrantsExported, null));
    
    return;
}

renderLayoutWithContentFile($variables);