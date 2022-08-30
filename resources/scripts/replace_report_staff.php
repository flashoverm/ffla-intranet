<?php
require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );

$mapping_table = array(
    "Würfl" => "Stephan Würfl",
    "Würfl St" => "Stephan Würfl",
    "Würf" => "Stephan Würfl",
    "Tomschi" => "Felix Tomschi",
    "Sperling" => "Michael Sperling",
    "Schwab" => "Nico Schwab",
    "Schwab N" => "Nico Schwab",
    "Schwab N." => "Nico Schwab",
    "Sperling M" => "Michael Sperling",
    "Papperger M." => "Michael Papperger",
    "Papperger" => "Michael Papperger",
    "Herweg" => "Gerald Herwig",
    "Mitulla Marvin" => "Marvin-Louis Mitulla",
    "Auhuber" => "Gerald Auhuber",
    "Neumeier Markus" => "Michael Meier",
    "Petschko Paul" => "Paul-Alexander Petschko",
    "Paintner M." => "Martin Paintner",
    "Möschl" => "Patrick Möschl",
    "Papperger" => "Michael Papperger",
    "DA Silva Monteiro" => "Milton Da Silva",
    "Strobl" => "Julian Strobl",
    "Fischer A" => "Anton Fischer",
    "Köppl C." => "Christian Köppl",
    "Dürschmidt" => "Sebastian Dürschmidt",
    "Pfeffer D." => "Dominik Pfeffer",
    "Schwab N." => "Nico Schwab",
    "Reinbacher R." => "Robert Reinbacher",
    "Möschl P" => "Patrick Möschl",
    "Alomari" => "Shadi Alomari",
    "Hermsdorf" => "Mathias Hermsdorf",
    "Scholz" => "Robert Scholz",
    "Wohnert, A." => "Alexander Wohnert",
    "Niklas Pupow" => "Nicklas Pupow",
    "Liebl" => "Oliver Liebl",
    "Borst" => "Christian Borst",
    "Josef Singer" => "Nick Singer",
    "Hermsdorf Matthias" => "Mathias Hermsdorf",
    "Guggenberger" => "Michael Guggenberger",
    "Koppauer D." => "Daniel Koppauer",
    "Hermsdorf Matthias" => "Mathias Hermsdorf",
    "Guggenberger" => "Michael Guggenberger",
    "Koppauer D." => "Daniel Koppauer",
    "Luger Stephan" => "Stefan Luger",
    "Schaak" => "Florian Schaak",
    "Fischer A." => "Anton Fischer",
    "Fritsch D." => "Daniel Fritsch",
    "Franz, M." => "Matthias Franz",
    "Seidel M." => "Miguel Seidel",
    "Forster A." => "Andreas Forster",
    "Al-Ssari" => "Hekmat Al-Ssari",
    "Busch R" => "Reinhard Busch",
    "Schwing, S." => "Florian Schwing",
    "Würfl Stefan" => "Stephan Würfl",
    "Fritz M." => "Marcus Fritz",
    "Einberger, T." => "Tobias Einberger",
    "Germowitz, J." => "Jochen Germowitz",
    "Paul Petschko" => "Paul-Alexander Petschko",
    "Singer Josef" => "Nick Singer",
    "Borst C" => "Christian Borst",
    "Mitulla" => "Marvin-Louis Mitulla",
    "Liebl O" => "Oliver Liebl",
    "Niko Schwab" => "Nico Schwab",
    "Hösl" => "Mathias Hösl",
    "Busch" => "Reinhard Busch",
    "Herwig" => "Gerald Herwig",
    "Möschl Patrik" => "Patrick Möschl",
    "Auerswald D." => "Daniela Auerswald",
    "Steierer C." => "Christian Steierer",
    "Guggenberger M." => "Michael Guggenberger",
    "Gschaider A." => "Andreas Gschaider",
    "Röhrig K." => "Kristina Röhrig",
    "Groß St," => "Stefan Groß",
    "Da Silva Cristian" => "Christian Da-Silva-Monteiro",
    "Da Silva Christian" => "Christian Da-Silva-Monteiro",
    "Röhrig T." => "Theresa Röhrig",
    "Rauchensteiner Alois" => "Alois Rauchensteiner jr.",
    "Kümmerer Karl Heinz" => "Karl-Heinz Kümmerer",
    "Daffner S." => "Stephan Daffner",
    "Matthias Daffner" => "Mathias Daffner",
    "Nachtmann K." => "Korbinian Nachtmann",
    "Pupow" => "Nicklas Pupow",
    "Mayer Robert" => "Robert Maier",
    "Johannes Süß" => "Johannes Sebastian Süß",
    "Schnur L." => "Ludwig Schnur",
    "Pupow" => "Nicklas Pupow",
    "Christian Da Silva" => "Christian Da-Silva-Monteiro",
    "Al-Ssarri" => "Hekmat Al-Ssari",
    "Strauch" => "Christian Strauch",
    "Josef Barth" => "﻿Josef Barth jun.",
    "Kolbeck Alex" => "Alexander Kolbeck",
    "Karl, S." => "Karl Schwaiger",
    "Maierhofer A." => "Andreas Maierhofer",
    "Hartauer Jisef" => "Josef Hartauer",
    "Seibold A." => "Andreas Seibold",
    "Dax Franz" => "Franz Dax jun.",
    "Walter J." => "Johannes Walter",
    "Robert Mair" => "Robert Maier",
    "Milton Montero" => "Milton Da Silva",
    "Da Sila Milton" => "Milton Da Silva",
);

$sql = "SELECT DISTINCT(name) FROM report_staff WHERE NOT length(name) = 36";

$sqlUser = "
        SELECT user.firstname, user.lastname, user.uuid
        FROM user
        WHERE ? LIKE CONCAT('%',user.lastname,'%')
        AND ? LIKE CONCAT('%',user.firstname,'%')
        ORDER BY user.lastname";

$sqlUpdate = "UPDATE report_staff SET name = ? WHERE name = ?";

updateUser($sql, $sqlUser, $sqlUpdate, true);

echo "With lastname\n";
$sqlUser = "
        SELECT user.firstname, user.lastname, user.uuid
        FROM user
        WHERE ? LIKE CONCAT('%',user.lastname,'%')
        ORDER BY user.lastname";

updateUser($sql, $sqlUser, $sqlUpdate, false, false);


function updateUser($sql, $sqlUser, $sqlUpdate, $dualparam, $doUpdate = true){
    global $db, $mapping_table;
    
    $statement = $db->prepare($sql);
    $statement->execute();
    while($row = $statement->fetch()) {
        
        $name = $row['name'];
        $name_ori = $name;
        echo $name_ori . "\n";
        
        if(isset($mapping_table[$name])){
            $name = $mapping_table[$name];
        }
        
        $paramArray = array($name);
        if($dualparam){
            $paramArray =  array($name, $name);
        }
        
        $statementUser = $db->prepare($sqlUser);
        if($statementUser->execute($paramArray)){
            $resultUser = $statementUser->fetch();
            if($resultUser){
                //echo "Replacing \t" . $name_ori . "\t with \t" . $resultUser['firstname'] . " " . $resultUser['lastname'] . "\n";
                //echo '    "' . $name_ori . '" => "' . $resultUser['firstname'] . " " . $resultUser['lastname'] . '"' . ",\n";
                
                if($doUpdate){
                    $statementUpdate = $db->prepare($sqlUpdate);
                    if($statementUpdate->execute(array($resultUser['uuid'], $name_ori))){
                        echo "Updated \n";
                    }
                }
            }
        }
    }
}




