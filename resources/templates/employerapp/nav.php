<?php

function left_navigation ($loggedIn){
	global $config;
	?>
        <li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["employerapp_home"] ?>/confirmations/'>Antragsübersicht</a>
		</li>
		<li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["employerapp_home"] ?>/confirmations/new'>Antrag erstellen</a>
		</li>
	<?php
	if (current_user_has_privilege(FFADMINISTRATION)) {
	?>
        <li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["employerapp_home"] ?>/confirmations/process'>Antragsbearbeitung</a>
		</li>
	<?php
	} else {

	}
}

function middle_navigation ($loggedIn){
	
}

function right_navigation ($loggedIn){

}
