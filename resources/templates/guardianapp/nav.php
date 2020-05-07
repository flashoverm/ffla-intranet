
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<button class="navbar-toggler" type="button" data-toggle="collapse"
		data-target="#navbarMainContent">
		<span class="navbar-toggler-icon"></span>
	</button>
		
	<?php if(!isset($noNav) || $noNav == false) {?>
	
	<div class='collapse navbar-collapse w-100' id='navbarMainContent'>
		<ul class='navbar-nav'>
			<li class='nav-item mx-1'>
				<a class='nav-link text-light' href='<?= $config["urls"] ["baseUrl"] ?>'>Home</a>
			</li>
			
<?php
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
				</li>
			</ul>
		</div>";
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
	?>
		</ul>
	</div>
<?php
}
?>
    <div class="collapse navbar-collapse w-100"
		id="navbarMainContent">
		<ul class="navbar-nav ml-auto">
<?php
if ($loggedIn) {
	
	if ($isAdmin) {
		echo "<li class='nav-item dropdown'>
        			<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>
						Administration
					</a>
        			<div class='dropdown-menu bg-dark'>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/manager'>Wachbeauftragte</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/user'>Personal</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/privilege'>Rechteverwaltung</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/events/admin'>Alle Wachen</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/reports/admin'>Alle Wachberichte</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["guardianapp_home"]. "/templates'>Personalvorlagen</a>
					</div>
				</li>";
	}
	echo "	<li class='dropdown'>
				<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>"
				. $_SESSION ['guardian_usermail'] . 
				"</a>
	        	<div class='dropdown-menu dropdown-menu-right bg-dark'>
					<a class='dropdown-item disabled text-secondary'>" . get_engine_obj_of_user($_SESSION ['guardian_userid'])->name . "</a>
					<div class='dropdown-divider'></div>
					<a class='dropdown-item text-light' href='" . $config["urls"]["intranet_home"]. "/change_password'>Passwort ändern</a>
					<a class='dropdown-item text-light' href='" . $config["urls"]["intranet_home"]. "/logout'>Abmelden</a>
				</div>
			</li>
";
} else {
	echo " 	<li class='nav-item'>
                <a class='nav-link text-light' href='" . $config["urls"]["intranet_home"]. "/login'>Anmelden</a>
            </li>";
	if ($config ["settings"] ["selfregistration"]) {
		echo " 	<li class='nav-item'>
                <a class='nav-link text-light' href='" . $config["urls"]["intranet_home"]. "/register'>Registrierung</a>
            </li>";
	}
}
?>
        </ul>
	</div>
	</nav> 
	<?php }?>
