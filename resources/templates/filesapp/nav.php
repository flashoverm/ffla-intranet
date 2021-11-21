<?php

function left_navigation ($currentUser){
	global $config;
	
	if ($currentUser->hasPrivilegeByName(Privilege::FILEADMIN)) {
		?>
	        <li class='nav-item'><a class='nav-link text-light'
				href='<?= $config["urls"]["filesapp_home"] ?>/admin'>Formulare</a>
			</li>
			<li class='nav-item'><a class='nav-link text-light'
				href='<?= $config["urls"]["filesapp_home"] ?>/new'>Formular
					Hochladen</a></li>
	<?php
	} else {
		?>
             <li class='nav-item'><a class='nav-link text-light'
				href='<?= $config["urls"]["filesapp_home"] ?>/overview'>Formulare</a></li>
	<?php
	}
}

function middle_navigation ($currentUser){
	
}

function right_navigation ($currentUser){
	global $config;
	?>
	<?php
}
