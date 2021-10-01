<?php
require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );

$reports = $reportDAO->getReports();

$mapping_table = array(
    "Würfl" => "Stephan Würfl",
    "Josef Singer" => "Nick Singer",
    "Papperger" => "Michael Papperger",
    "Monteiro Milton" => "Milton Da Silva",
    "Milton Monteiro" => "Milton Da Silva",
    "Busch" => "Reinhard Busch",
    "Dürschmidt" => "Sebastian Dürschmidt",
    "Thral M." => "Markus Thral",
    "Herwig" => "Gerald Herwig",
    "Busch R." => "Reinhard Busch",
    "Seidel M." => "Miguel Seidel"
);

foreach ($reports as $report) {
    $creator = $report->getCreator();
    
    if($creator != null && $creator != "" && ! strpos($creator, "-")){
        
        $creator_ori = $creator;
        
        if(isset($mapping_table[$creator])){
            $creator = $mapping_table[$creator];
        }
        
        $sql = "
        SELECT user.firstname, user.lastname, user.uuid
        FROM user
        WHERE ? LIKE CONCAT('%',user.lastname,'%')
        AND ? LIKE CONCAT('%',user.firstname,'%')
        ORDER BY user.lastname
    ";
        /*
        echo $sql;
        $result1 = $db->query($sql);
        $row = $result1->fetch();
        var_dump($row);
        */
        
        
        $statement = $db->prepare($sql);
        if($statement->execute(array($creator, $creator))){
            $result = $statement->fetch();
            if($result){
                echo "Replacing " . $creator_ori . " with " . $result['firstname'] . " " . $result['lastname'] . "\n";
                
                $report->setCreator($result['uuid']);
                $reportDAO->save($report);
            } else {
                echo "Cannot replace " . $creator . "\n";
            }
            
        }
        
        
    }
    
}