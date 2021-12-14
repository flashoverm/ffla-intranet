<?php
class PrivilegeUtil {

	static function userIsCurrentUser(User $user){
		if($user->getUuid() == SessionUtil::getCurrentUserUUID()){
			return true;
		}
		return false;
	}
	
	static function checkPermission($requiredPrivilege){
		global $currentUser;
		
		if(isset($currentUser) && $currentUser){
			
			if( $currentUser->hasPrivilegeByName($requiredPrivilege) ){
				return true;
			}
		}
		return false;
	}
	
	static function userHasPrivilege($requiredPrivilege, $continue = false){
		if(PrivilegeUtil::checkPermission($requiredPrivilege)){
			return true;
		}
		http_response_code(403);
		if($continue){
			return false;
		}
		exit();
	}
	
	static function userHasOnePrivilege(array $requiredPrivileges, $continue = false){
		foreach($requiredPrivileges as $requiredPrivilege){
			if(PrivilegeUtil::checkPermission($requiredPrivilege)){
				return true;
			}
		}
		http_response_code(403);
		if($continue){
			return false;
		}
		exit();
	}
}
