<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
    'app' => $config["apps"]["guardian"],
    'template' => "reportUserStatistics_template.php",
    'title' => 'Eigene Wachstatistik',
    'secured' => true
);
checkSitePermissions($variables);

$statisticData = array();

$userUuid = '0EDC6EEC-AD3A-02FF-AE50-87EBF5FCE0F0';
$userUuid = $userController->getCurrentUser()->getUuid();

$reports = $reportDAO->getReportsByStaff($userUuid);

if($reports){
    foreach ($reports as $report){
        $year = date('Y', strtotime($report->getDate()));
        
        if( ! array_key_exists($year, $statisticData) ){
            $statisticData[$year] = array(
                1,
                0
            );
            
        } else {
            $statisticData[$year] = array(
                $statisticData[$year][0] + 1,
                $statisticData[$year][1]
            );
            
        }
        
        foreach( $report->getUnits() as $unit ){
            foreach($unit->getStaff() as $staff){
                if($staff->getUser()->getUuid() == $userUuid){
                    $statisticData[$year] = array(
                        $statisticData[$year][0],
                        $statisticData[$year][1] + $unit->getUnitOperatingMinutes()
                    );
                }
            }
        }
        
    }
}

$variables['statisticData'] = $statisticData;

renderLayoutWithContentFile($variables);