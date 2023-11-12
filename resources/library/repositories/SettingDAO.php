<?php


class SettingDAO {
    
    const RECEIVE_NO_MAIL_ON_NEW_EVENT = "RECEIVE_NO_MAIL_ON_NEW_EVENT";
    const IMMEDIATE_CONFIRMATION = "IMMEDIATE_CONFIRMATION";
    
    private function __construct()  {
        $this->data = array (
            self::RECEIVE_NO_MAIL_ON_NEW_EVENT => new Setting(self::RECEIVE_NO_MAIL_ON_NEW_EVENT, "Keine E-Mail-Benachrichtigung bei neu eingestellten Wachen", Setting::CAT_EVENTS, Privilege::EVENTPARTICIPENT),
            //self::IMMEDIATE_CONFIRMATION => new Setting(self::IMMEDIATE_CONFIRMATION, "Sofortige Arbeitgeberbestätigung (ohne Freigabe)", Setting::CAT_CONFIRMATION, null),
        );
    }
    
    public static function getSettingsByUser(User $currentUser){
        $settings = self::getAllSettings();
        $usersSettings = array();
        foreach ($settings as $setting){
            if($setting->getPrivilege() == null || $currentUser->hasPrivilegeByName($setting->getPrivilege())){
                $usersSettings[] = $setting;
            }
        }
        return $usersSettings;
    }
    
    public static function getAllSettings(){
        $settings = self::getInstance()->getData();
        usort($settings, fn($a, $b) => strcmp($a->getCategory(), $b->getCategory()));
        return $settings;
    }
    
    public static function getCategories(){
        $settings = getAllSettings();
        $categories = array();
        foreach ($settings as $setting){
            $categories[] = $setting->getCategory();
        }
        $categories = array_unique($categories);
        sort($categories);
        return $categories;
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
	