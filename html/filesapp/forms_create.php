<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_files.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Formular hochladen",
    'secured' => true,
	'right' => FILEADMIN
);

if(isset($_FILES['upload']) && isset($_POST['description'])){
    
    $description = trim($_POST['description']);
    
    //TODO check if file exists!
    
    //https://www.tutorialspoint.com/php/php_file_uploading.htm
    //MAX: 32MB
    
    if (!file_exists($config["paths"]["files"])) {
        mkdir($config["paths"]["files"], 0777, true);
    }
	
    $result = move_uploaded_file($_FILES['upload']['tmp_name'], $config["paths"]["files"].$_FILES['upload']['name']);
    
    if($result){
      
        if(insert_file($description, date('Y-m-d H:i:s', time()), $_FILES['upload']['name'])){
            $variables ['successMessage'] = "Datei wurde hochgeladen";
            header ( "Location: " . $config["urls"]["filesapp_home"] . "/forms/admin" ); // redirects
                        
        } else {
            $variables ['alertMessage'] = "Datei hochladen fehlgeschlagen";
        }
        
    } else {
        $variables ['alertMessage'] = "Datei konnte nicht hochgeladen werden";
    }
}

renderLayoutWithContentFile($config["apps"]["files"], "formsCreate_template.php", $variables);
