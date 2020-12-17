<?php

function left_navigation ($loggedIn){
	global $config;
	
	if ($loggedIn) {
		
		echo "
			<li class='dropdown'>
				<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>Hydranten</a>
        		<div class='dropdown-menu bg-dark'>
        			<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/all'>Alle Hydranten</a>
					<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/search'>Hydrantensuche</a>";
		if(current_user_has_privilege(HYDRANTADMINISTRATOR)){
			echo "<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/new'>Hydrant anlegen</a>";
		}
		echo "	</div>
			</li>";
		
		echo "<li class='nav-item'>
        		<a class='nav-link text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/search'>Hydrantenkarten</a>
			</li>";
		
		if(current_user_has_privilege(ENGINEHYDRANTMANANGER)){
			echo "
			<li class='dropdown'>
				<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>Hydrantenprüfung</a>
        		<div class='dropdown-menu bg-dark'>
		        	<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection'>Prüfungsübersicht</a>
					<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection/prepare'>Prüfung planen</a>
                    <a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection/assist'>Neue Prüfung (Assistent)</a>
		        	<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection/edit'>Neue Prüfung</a>
		        	<a class='dropdown-item text-light' target='_blank' href='" . $config ["urls"] ["filesapp_home"] . "/hydrant-form.pdf'>Download Formblatt</a>
				</div>
			</li>";
		}
	}
}

function middle_navigation ($loggedIn){
	
}

function right_navigation ($loggedIn){
	global $config;
	
	?>
	<li class='nav-item'>
		<a class='nav-link text-light' href="<?= $config["urls"]["hydrantapp_home"]?>/manual" data-toggle="tooltip" title="Anleitung">&#9432;</a>
	</li>
	<?php 
}
