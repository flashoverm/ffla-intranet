<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

//$users = $userDAO->getUsersByEngineAndPrivilege($engineUuid, Privilege::EVENTPARTICIPENT);

if(! SessionUtil::userLoggedIn()){
    http_response_code(401);
} else {
    if (isset($_GET['engine']) && isset($_GET['fieldid']) ) {
	    $engine = trim($_GET['engine']);
	    $users = $userDAO->getUsersByEngineAndPrivilege($engine, Privilege::EVENTPARTICIPENT);
        if($users){           
            header('Content-Type: application/json');
            echo "{ fieldid: \"" . $_GET['fieldid'] . "\", status: 200, users: [\n";
            foreach ($users as $key=>$user){
                if($key == (count($users)-1) ){
                    echo getUserString($user) . "\n";
                    break;
                }
                echo getUserString($user) . ",\n";
            }
            echo "]}";
        } else {
            echo "{ fieldid: \"" . $_GET['fieldid'] . "\", status: 404}";
        }
	    
	} else {
		http_response_code(400);
	}
}

function getUserString($user){
    return '{"uuid":"' . $user->getUuid() . '", "name":"' . $user->getFullNameLastNameFirst() . '"}';
}
?>