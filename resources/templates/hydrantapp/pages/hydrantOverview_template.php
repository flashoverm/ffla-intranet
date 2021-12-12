<?php
if (! count ( $hydrants )) {
	showInfo ( "Keine Hydranten gefunden" );
} else {
    $options = array(
        'showEngine' => true,
    );
    
    render(TEMPLATES_PATH . "/hydrantapp/elements/hydrant_table.php", $hydrants, $options);
}
?>
