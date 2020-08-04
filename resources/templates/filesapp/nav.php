<?php

function left_navigation ($loggedIn){
	global $config;
	
	if (current_user_has_privilege(FILEADMIN)) {
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
