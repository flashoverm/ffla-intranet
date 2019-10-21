<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));

$util = array (
		
		"head" => "Lieber Nutzer, \n\n",
		"footer" => "\n\n\nIntranet der Freiwilligen Feuerwehr der Stadt Landshut\n\n" . $config ["urls"] ["base_url"] . $config ["urls"] ["intranet_home"]
);

$bodies = array (
    
    "report_insert" => $util["head"] . "es wurde eine ein neuer Hydranten-Prüfbericht angelegt. Diesen finden Sie im Anhang oder im Portal unter \n\n",
    
    "report_update" => $util["head"] . "ein Hydranten-Prüfbericht wurde aktualisiert. Diesen finden Sie im Anhang oder im Portal unter \n\n",
    
);