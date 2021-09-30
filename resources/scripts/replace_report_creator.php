<?php
require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );

$reports = $reportDAO->getReports();

foreach ($reports as $report) {
    $creator = $report->getCreator();
    
    $sql = " 
        SELECT user.firstname, user.lastname, user.uuid
        FROM user 
        WHERE ? LIKE CONCAT('%',user.lastname,'%')
        AND ? LIKE CONCAT('%',user.firstname,'%')
        ORDER BY user.lastname
    ";
    
    $statement = $db->prepare($sql);
    if($statement->execute(array($creator, $creator))){
        $result = $statement->fetch();
        echo "Replacing " . $creator . " with " . $result['firstname'] . " " . $result['lastname'];
    }
}