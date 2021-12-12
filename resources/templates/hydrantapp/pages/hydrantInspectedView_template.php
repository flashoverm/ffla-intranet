<?php
if (! count ( $hydrants->getData() )) {
    showInfo ( "Keine Hydranten für diesen Zug gefunden" );
} else {
    $options = array(
        'showLastCheck' => true,
    );
    
    render(TEMPLATES_PATH . "/hydrantapp/elements/hydrant_table.php", $hydrants, $options);
 
}
?>