<?php

function left_navigation ($currentUser){
	global $config;
	?>
        <li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["masterdataapp_home"] ?>/confirmations/'>Offene Änderungen</a>
		</li>
		<li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["masterdataapp_home"] ?>/confirmations/new'>Änderung beantragen</a>
		</li>
	<?php
	if ($currentUser->hasPrivilegeByName(Privilege::MASTERDATAADMIN)) {
	?>
        <li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["masterdataapp_home"] ?>/confirmations/process'>Änderungen bearbeiten</a>
		</li>
	<?php
	}
}

function middle_navigation ($currentUser){
	
}

function right_navigation ($currentUser){

}
