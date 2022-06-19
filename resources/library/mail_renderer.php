<?php

function renderMail($recipient, $template, array $parameter){
    
    $parameter = addDefaultMailParameters($parameter);
    
    $content = render_php($template, $parameter);
    
    return renderMailHead($recipient) . $content;    
}

function renderMailHead($recipient){
    $head = "Lieber ";
    
    if(isset($recipient)){
        $head .= $recipient->getFirstname();
    } else {
        $head .= "Benutzer";
    }
    
    $head .= ",\n\n";
    
    return $head;
}

function addDefaultMailParameters(array $parameter){
    global $config;
    
    $parameter['portal_url'] = $config ["urls"] ["base_url"];
    
    return $parameter;
}

function render_php($path, array $variables = array()){
    ob_start();
    
    if (count ( $variables ) > 0) {
        foreach ( $variables as $key => $value ) {
            if (strlen ( $key ) > 0) {
                ${$key} = $value;
            }
        }
    }
    
    include($path);
    $var=ob_get_contents();
    ob_end_clean();
    return $var;
}