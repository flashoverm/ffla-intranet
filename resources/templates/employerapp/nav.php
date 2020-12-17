<?php

function left_navigation ($currentUser){
	global $config;
	?>
        <li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["employerapp_home"] ?>/confirmations/'>Antragsübersicht</a>
		</li>
		<li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["employerapp_home"] ?>/confirmations/new'>Antrag erstellen</a>
		</li>
	<?php
	if ($currentUser->hasPrivilegeByName(Privilege::FFADMINISTRATION)) {
	?>
        <li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["employerapp_home"] ?>/confirmations/process'>Antragsbearbeitung</a>
		</li>
	<?php
	} else {

	}
}

function middle_navigation ($currentUser){
	
}

function right_navigation ($currentUser){

}
