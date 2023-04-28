<?php


class SettingDAO {
    
    const RECEIVE_NO_MAIL_ON_NEW_EVENT = "RECEIVE_NO_MAIL_ON_NEW_EVENT";
    const IMMEDIATE_CONFIRMATION = "IMMEDIATE_CONFIRMATION";
    
    private function __construct()  {
        $this->data = array (
            self::RECEIVE_NO_MAIL_ON_NEW_EVENT => new Setting(self::RECEIVE_NO_MAIL_ON_NEW_EVENT, "Keine Benachrichtigung bei neu eingestellten Wachen"),
            self::IMMEDIATE_CONFIRMATION => new Setting(self::IMMEDIATE_CONFIRMATION, "Sofortige Arbeitgeberbestätigung (ohne Freigabe)"),
        );
    }
    
    public static function getAllSettings(){
        return self::getInstance()->getData();
    }
    
    public static function getSetting(string $key){
        if(isset(self::getInstance()->getData()[$key])){
            return self::getInstance()->getData()[$key];
        }
        return false;
    }
    
    /*
     * Singleton Pattern
     */
    
    private ?array $data = null;

    private static $instance = null;
    
    private static function getInstance() {
        if (self::$instance == null)
        {
            self::$instance = new SettingDAO();
        }
        
        return self::$instance;
    }
    
    private function getData(){
        return $this->data;
    }


    
    
}
	