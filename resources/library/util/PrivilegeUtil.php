<?php
class PrivilegeUtil {
	
	static function checkRule(array $rule){
		/*
		 * rule:  combination of user, engine or single privilege (AND)
		 */
		if(isset($rule['user'])){
			if( ! PrivilegeUtil::checkRequiredUser($rule['user'])){
				return false;
			}
		}
		if(isset($rule['privilege'])){
			if( ! PrivilegeUtil::checkPrivileges($rule['privilege'])){
				return false;
			}
		}
		if(isset($rule['engine'])){
			if( ! PrivilegeUtil::checkRequiredEngine($rule['engine'])){
				return false;
			}
		}
		
		return true;
	}

	static function checkRequiredUser(User $user){
		if($user->getUuid() == SessionUtil::getCurrentUserUUID()){
			return true;
		}
		return false;
	}
	
	static function checkRequiredEngine(Engine $engine){
		global $currentUser;
		
		if($currentUser->hasEngine($engine)){
			return true;
		}
		return false;
	}
	
	static function checkPrivileges($privilege){
		if(is_array($privilege)){
			//Multible privileges possible (just one necessary)
			foreach($privilege as $privilege){
				if(PrivilegeUtil::checkPrivilege($privilege)){
					return true;
				}
			}
		} else {
			if(PrivilegeUtil::checkPrivilege($privilege)){
				return true;
			}
		}
		return false;
	}
	
	private static function checkPrivilege($requiredPrivilege){
		global $currentUser;
		
		if(isset($currentUser) && $currentUser){
			
			if( $currentUser->hasPrivilegeByName($requiredPrivilege) ){
				return true;
			}
		}
		return false;
	}
}
