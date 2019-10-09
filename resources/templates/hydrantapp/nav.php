
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<button class="navbar-toggler" type="button" data-toggle="collapse"
		data-target="#navbarMainContent" aria-controls="navbarMainContent"
		aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

<?php
if ($loggedIn) {

	echo "<div class='collapse navbar-collapse' id='navbarMainContent'>
		  <ul class='navbar-nav mr-auto'>
            <li class='nav-item'>
        		<a class='nav-link text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/search'>Hydrantenkarten</a>
			</li>";
	
	if(userHasRight(ENGINEHYDRANTMANANGER)){
		echo "
			<li class='dropdown'>
				<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>Hydrantenprüfung</a>
        		<div class='dropdown-menu bg-dark'>
		        	<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection'>Prüfungsübersicht</a>
					<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection/candidates'>Aktuelle Prüfobjekte</a>
                    <a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection/assist'>Neue Prüfung (Assistent)</a>
		        	<a class='dropdown-item text-light' href='" . $config ["urls"] ["hydrantapp_home"] . "/inspection/edit'>Neue Prüfung</a>
		        	<a class='dropdown-item text-light' href='" . $config ["urls"] ["filesapp_home"] . "/hydrant-form.pdf'>Download Formblatt</a>
				</div>
			</li>
		</ul>
		</div>";
	} else {
	    echo " 
	    </ul>
		</div>";
	}
}

?>
    <div class="collapse navbar-collapse" id="navbarMainContent">
		<ul class="navbar-nav ml-auto">
<?php
if ($loggedIn) {
	echo "	
			<li class='dropdown'>
				<a class='nav-link dropdown-toggle text-light' data-toggle='dropdown' href='#'>" . $_SESSION ['intranet_username'] . "
				</a>
        		<div class='dropdown-menu dropdown-menu-right bg-dark'>
					<a class='dropdown-item disabled text-secondary'>" . get_engine_obj_of_user($_SESSION ['intranet_userid'])->name . "</a>
					<div class='dropdown-divider'></div>
		        	<a class='dropdown-item text-light' href='" . $config ["urls"] ["intranet_home"] . "'>Intranet Home</a>
		        	<a class='dropdown-item text-light' href='" . $config ["urls"] ["intranet_home"] . "/logout'>Abmelden</a>
				</div>       			
			</li>
";
} else {
	echo " 	<li class='nav-item'>
                <a class='nav-link text-light' href='" . $config ["urls"] ["intranet_home"] . "/login'>Anmelden</a>
            </li>";
}
?>
<!--
			<li class='nav-item'>
				<a class='nav-link text-light' href="<?= $config["urls"]["hydrantapp_home"]?>/manual" data-toggle="tooltip" title="Anleitung">&#9432;</a>
            </li>
-->
		</ul>
	</div>
</nav>
