<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
    'app' => $config["apps"]["guardian"],
    'template' => "reportStatistics_template.php",
    'title' => 'Wach-Statistiken',
    'secured' => true
);
checkSitePermissions($variables);

$engines = $engineDAO->getEngines();
$variables['engines'] = $engines;
$years = $reportDAO->getReportYears();
$variables['years'] = $years;

$year = date("Y");
if(isset($_GET ['year'])){
    $year = $_GET ['year'];
}

    $reports = $reportDAO->getReportsByYear($year);

$statisticData = array();

foreach ($reports as $report){
    $reportEngineUuid = $report->getEngine()->getUuid();
    
    
    if( ! array_key_exists($reportEngineUuid, $statisticData) ){        
        $statisticData[$reportEngineUuid] = array(
            1,
            0,
            0
        );
    } else {
        $statisticData[$reportEngineUuid] = array(
            $statisticData[$reportEngineUuid][0] + 1,
            $statisticData[$reportEngineUuid][1],
            $statisticData[$reportEngineUuid][2]
        );
        
    }
    
    foreach( $report->getUnits() as $unit ){
    
        foreach($unit->getStaff() as $staff){
            if($staff->getUser() != null){
                $staffEngineUuid = $staff->getUser()->getEngine()->getUuid();
            } else {
                $staffEngineUuid = $staff->getEngine()->getUuid();
            }
                
            
            if( ! array_key_exists($staffEngineUuid, $statisticData) ){
                $statisticData[$staffEngineUuid] = array(
                    0,
                    1,
                    $unit->getUnitOperatingMinutes()
                );
            } else {
                $statisticData[$staffEngineUuid] = array(
                    $statisticData[$staffEngineUuid][0],
                    $statisticData[$staffEngineUuid][1] + 1,
                    $statisticData[$staffEngineUuid][2] + $unit->getUnitOperatingMinutes()
                );
            }
        }
        
        
    }
}

$variables['statisticData'] = $statisticData;

renderLayoutWithContentFile($variables);
