<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Formular-Verwaltung",
    'secured' => true,
	'privilege' => FILEADMIN
);


if (isset($_POST['delete'])) {
    
    $uuid = trim($_POST['delete']);
    
    $file = get_file($uuid);
    $log = LogbookEntry::fromAction(LogbookActions::FileDeleted, $uuid);
    if(delete_file_fs($file)){
        
        if(delete_file($uuid)){
        	insert_logbook_entry($log);
            $variables ['successMessage'] = "Datei " . $file->description . " wurde entfernt";
            
        } else {
            $variables ['alertMessage'] = "Entfernen fehlgeschlagen";
        }
        
    } else {
        
        //check if file is there - if not, remove db entry
        
        $variables ['alertMessage'] = "Entfernen fehlgeschlagen";
    }
}

$variables ['files'] = get_files();

renderLayoutWithContentFile($config["apps"]["files"], "formsAdmin_template.php", $variables);

function delete_file_fs($file){
    global $config;
    
    return unlink($config["paths"]["files"] . $file->filename);
}
?>
