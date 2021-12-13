<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["files"],
		'template' => "formsAdmin_template.php",
	    'title' => "Formular-Verwaltung",
	    'secured' => true,
		'privilege' => Privilege::FILEADMIN
);
$variables = checkSitePermissions($variables);

if (isset($_POST['delete'])) {
    
    $uuid = trim($_POST['delete']);
    
    $file = $fileDAO->getFile($uuid);
    $log = LogbookEntry::fromAction(LogbookActions::FileDeleted, $uuid);
    if(delete_file_fs($file)){
        
        if($fileDAO->deleteFile($uuid)){
        	$logbookDAO->save($log);
            $variables ['successMessage'] = "Datei " . $file->getDescription() . " wurde entfernt";
            
        } else {
            $variables ['alertMessage'] = "Entfernen fehlgeschlagen";
        }
        
    } else {
        
        //check if file is there - if not, remove db entry
        
        $variables ['alertMessage'] = "Entfernen fehlgeschlagen";
    }
}

$variables ['files'] = $fileDAO->getFiles();

renderLayoutWithContentFile($variables);

function delete_file_fs(File $file){
    global $config;
    
    return unlink($config["paths"]["files"] . $file->getFilename());
}
?>
