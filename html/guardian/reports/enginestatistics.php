<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
    'app' => $config["apps"]["guardian"],
    'template' => "reportEngineStatistics_template.php",
    'title' => 'Gesamt-Wachstatistik',
    'secured' => true,
    'privilege' => Privilege::EVENTMANAGER
);
checkSitePermissions($variables);

$years = $reportDAO->getReportYears();
$variables['years'] = $years;

$year = date("Y");
if(isset($_GET ['year'])){
    $year = $_GET ['year'];
}

$userEngine = $userController->getCurrentUser()->getEngine()->getUuid();

$reports = $reportDAO->getReportsByYear($year);

$statisticData = array();

foreach ($reports as $report){
    foreach( $report->getUnits() as $unit ){
        foreach($unit->getStaff() as $staff){
            
            $user = $staff->getUser();
            if($user != null && $user->getEngine()->getUuid() == $userEngine){
                $userUuid = $user->getUuid();
                
                
                if( ! array_key_exists($userUuid, $statisticData) ){
                    $statisticData[$userUuid] = array(
                        $user,
                        1,
                        $unit->getUnitOperatingMinutes()
                    );
                } else {
                    $statisticData[$userUuid] = array(
                        $statisticData[$userUuid][0],
                        $statisticData[$userUuid][1] + 1,
                        $statisticData[$userUuid][2] + $unit->getUnitOperatingMinutes()
                    );
                    
                }
            }
        }
    }
}

$variables['statisticData'] = $statisticData;

renderLayoutWithContentFile($variables);