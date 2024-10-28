<?php

require_once "BaseDAO.php";

class PrivilegeDAO {
	
    public function __construct() {
		$this->data = array(
		    Privilege::ENGINEMANAGER =>
		    new Privilege(Privilege::ENGINEMANAGER, "Personalverwaltung (Zug)", false),
		    Privilege::FILEADMIN =>
		    new Privilege(Privilege::FILEADMIN, "Dateiadministrator", false),
		    Privilege::FFADMINISTRATION =>
		    new Privilege(Privilege::FFADMINISTRATION, "Feuerwehrverwaltung/Geschäftszimmer", false),
		    Privilege::ENGINEHYDRANTMANANGER =>
		    new Privilege(Privilege::ENGINEHYDRANTMANANGER, "Hydrantenverwaltung (Zug)", false),
		    Privilege::HYDRANTADMINISTRATOR =>
		    new Privilege(Privilege::HYDRANTADMINISTRATOR, "Hydrantenadministrator", false),
		    Privilege::PORTALADMIN =>
		    new Privilege(Privilege::PORTALADMIN, "Portaladministrator", false),
		    Privilege::EDITUSER =>
		    new Privilege(Privilege::EDITUSER, "Eigenen Benutzer bearbeiten", true),
		    Privilege::EVENTPARTICIPENT =>
		    new Privilege(Privilege::EVENTPARTICIPENT, "Wachteilnahme", true),
		    Privilege::EVENTMANAGER =>
		    new Privilege(Privilege::EVENTMANAGER, "Wachverwaltung (Zug)", false),
		    Privilege::EVENTADMIN =>
		    new Privilege(Privilege::EVENTADMIN, "Wachadministrator", false),
		    Privilege::MASTERDATAADMIN =>
		    new Privilege(Privilege::MASTERDATAADMIN, "Stammdaten-Administrator", false),
		    Privilege::ENGINECONFIRMATIONMANAGER =>
		      new Privilege(Privilege::ENGINECONFIRMATIONMANAGER, "Arbeitgeberbestätigungsverwaltung (Zug)", false)
		);
	}

	public function getPrivileges(){
	    $privileges = self::getInstance()->getData();
	    usort($privileges, fn($a, $b) => strcmp($a->getPrivilege(), $b->getPrivilege()));
	    return $privileges;
	}

	public function getPrivilege(String $uuid){
	    $privileges = self::getPrivileges();
	    foreach ($privileges as $privilege){
	        if($privilege->getUuid() == $uuid){
	            return $privilege;
	        }
	    }
	    return false;
	}
	
	/*
	 * Duplicate because priviledge used to be a database object
	 */
	public function getPrivilegeByName(String $name){
	    return getPrivilege($name);
	}
	
	/*
	 * Singleton Pattern
	 */
	
	private ?array $data = null;
	
	private static $instance = null;
	
	private static function getInstance() {
	    if (self::$instance == null)
	    {
	        self::$instance = new PrivilegeDAO();
	    }
	    
	    return self::$instance;
	}
	
	private function getData(){
	    return $this->data;
	}
	
}
