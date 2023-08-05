<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";


// Pass variables (as an array) to template
$variables = array (
    'app' => $config["apps"]["users"],
    'template' => "settings_template.php",
    'secured' => true,
    'title' => "Einstellungen",
);

checkSitePermissions($variables);

$user = $userController->getCurrentUser();

$settings = SettingDAO::getSettingsByUser($user);
$variables ['settings'] = $settings;

if(isset($_POST['updateSettings'])){
    $newSettings = array();
    foreach($settings as $setting){
        
        $inputName = "setting_" . $setting->getKey();
        if(isset ( $_POST [ $inputName ] )){
            $newSettings [] = $setting;
        }
    }
    
    $user->setSettings($newSettings);
    $user = $userDAO->save($user);
    
    if($user){
        $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::UserUpdated, $user->getUuid()));
        $variables ['successMessage'] = "Der Benutzer wurde aktualisiert";
    } else {
        $variables ['alertMessage'] = "Der Benutzer konnte nicht aktualisiert werden";
    }
}

$variables['user'] = $user;

renderLayoutWithContentFile ($variables );
