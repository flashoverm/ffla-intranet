
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<button class="navbar-toggler" type="button" data-toggle="collapse"
		data-target="#navbarMainContent" aria-controls="navbarMainContent"
		aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class='collapse navbar-collapse' id='navbarMainContent'>
		<ul class='navbar-nav mr-auto'>
<?php
if (userHasRight(FILEADMIN)) {
	?>
	                <li class='nav-item'><a class='nav-link text-light'
				href='<?= $config["urls"]["filesapp_home"] ?>/forms/admin'>Formulare</a>
			</li>
			<li class='nav-item'><a class='nav-link text-light'
				href='<?= $config["urls"]["filesapp_home"] ?>/forms/new'>Formular
					Hochladen</a></li>
<?php
} else {
	?>
             <li class='nav-item'><a class='nav-link text-light'
				href='<?= $config["urls"]["filesapp_home"] ?>/forms'>Formulare</a></li>
<?php
}
?>
        </ul>
	</div>
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