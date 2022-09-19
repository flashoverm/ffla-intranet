<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

//$users = $userDAO->getUsersByEngineAndPrivilege($engineUuid, Privilege::EVENTPARTICIPENT);

if(! SessionUtil::userLoggedIn()){
    http_response_code(401);
} else {
	if (isset($_GET['name'])) {
	    $name = trim($_GET['name']);
	    if(strlen($name) >= 3){
	        $users = $userDAO->getUsersTypeaheadWithPrivilegeByName($name, Privilege::EVENTPARTICIPENT);
	        if($users){
	            header('Content-Type: application/json');
	            echo "[\n";
	            foreach ($users as $key=>$user){
	                if($key > 10 || $key == (count($users)-1) ){
	                    echo getUserString($user) . "\n";
	                    break;
	                }
	                echo getUserString($user) . ",\n";
	            }
	            echo "]";
	        } else {
	            http_response_code(404);
	        }
	    }
	    
	} else {
		http_response_code(400);
	}
}

function getUserString($user){
    return '{"uuid":"' . $user->getUuid() . '", "name":"' . $user->getFullName() . '"}';
}
?>