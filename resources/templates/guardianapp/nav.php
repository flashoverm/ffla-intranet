<?php

function left_navigation ($loggedIn){
	global $config;
		
	if ($loggedIn) {
		echo "      <li class='nav-item dropdown'>
        			<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>
						Wachen
					</a>
        			<div class='dropdown-menu bg-dark'>";
		echo "<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/events'>Wachübersicht</a>";
		if (current_user_has_privilege (EVENTMANAGER)){
			echo "<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/events/new'>Wache anlegen</a>";
		}
		echo "			</div>
				</li>
				<li class='nav-item dropdown'>
        			<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>
						Wachberichte
					</a>
        			<div class='dropdown-menu bg-dark'>";
		if (current_user_has_privilege (EVENTMANAGER)){
			echo "<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/reports'>Berichtsübersicht</a>";
		}
		echo "<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/reports/new'>Bericht anlegen</a>";
		if (current_user_has_privilege (EVENTMANAGER)){
			echo "<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/reports/export'>Berichte exportieren</a>";
		}
		echo "				</div>
				</li>>";
	} else {
		if($config ["settings"] ["publicevents"]){
			echo "	<li class='nav-item mx-1'>
	        		<a class='nav-link text-light' href='" . $config["urls"]["guardianapp_home"]. "/events/public'>Öffentliche Wachen</a>
				</li>";
		}
		if($config ["settings"] ["reportfunction"]){
			echo "<li class='nav-item mx-1'>
		        	<a class='nav-link text-light' href='" . $config["urls"]["guardianapp_home"]. "/reports/new'>Wachbericht erstellen</a>
			  </li>";
		}
	}
}

function middle_navigation ($loggedIn){
	
}

function right_navigation ($loggedIn){
	global $config;
	
	if (current_user_has_privilege(EVENTADMIN)) {
		echo "<li class='nav-item dropdown'>
        			<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>Administration</a>
        			<div class='dropdown-menu bg-dark'>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/events/admin'>Alle Wachen</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/reports/admin'>Alle Wachberichte</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/templates'>Personalvorlagen</a>
					</div>
				</li>";
	}
}