<?php

function left_navigation ($currentUser){
	global $config;
	?>
        <li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["employerapp_home"] ?>/confirmations/overview'>Antragsübersicht</a>
		</li>
		<li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["employerapp_home"] ?>/confirmations/new'>Antrag erstellen</a>
		</li>
	<?php
	if ($currentUser->hasPrivilegeByName(Privilege::FFADMINISTRATION)
	    || $currentUser->hasPrivilegeByName(Privilege::ENGINECONFIRMATIONMANAGER)) {
	?>
        <li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["employerapp_home"] ?>/confirmations/process'>Antragsbearbeitung</a>
		</li>
	<?php
	}
}

function middle_navigation ($currentUser){
    //No menu
}

function right_navigation ($currentUser){
    //No menu
}
