<?php

function left_navigation ($currentUser){
	global $config;
	
	if ($currentUser) {
		
		echo "
			<li class='dropdown'>
				<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>Hydranten</a>
        		<div class='dropdown-menu bg-dark'>
        			<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/all'>Alle Hydranten</a>
					<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/search'>Hydrantensuche</a>";
					if($currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){
						echo "<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/new'>Hydrant anlegen</a>";
					}
		echo "	</div>
			</li>";
		
		echo "<li class='nav-item'>
        		<a class='nav-link text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/search'>Hydrantenkarten</a>
			</li>";
		
		if($currentUser->hasPrivilegeByName(Privilege::ENGINEHYDRANTMANANGER) || $currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){
			echo "
			<li class='dropdown'>
				<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>Hydranten prüfen</a>
        		<div class='dropdown-menu bg-dark'>
					<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection/prepare'>Prüfung planen</a>
		        	<a class='dropdown-item text-light' target='_blank' href='" . $config ["urls"] ["filesapp_home"] . "/hydrant-form.pdf'>Download Formblatt</a>
					<div class='dropdown-divider'></div>
                    <a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection/assist'>Prüfung eingeben (Assistent)</a>
		        	<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection/edit'>Prüfung eingeben</a>
				</div>
			</li>
			<li class='dropdown'>
				<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>Prüfungen verwalten</a>
        		<div class='dropdown-menu bg-dark'>
		        	<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection'>Prüfungsübersicht</a>
					<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspected'>Geprüfte Hydranten</a>";
			if($currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){
				echo "<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection/evaluation'>Jahres-Auswertung</a>";
			}
			echo "
				</div>
			</li>";
		}
	}
}

function middle_navigation ($currentUser){
	
}

function right_navigation ($currentUser){
	global $config;
	
	?>
	<li class='nav-item'>
		<a class='nav-link text-light' href="<?= $config["urls"]["hydrantapp_home"]?>/manual" data-toggle="tooltip" title="Anleitung">&#9432;</a>
	</li>
	<?php 
}
