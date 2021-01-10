<?php

function left_navigation ($currentUser){
	global $config;
	?>
        <li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests/'>Antragsübersicht</a>
		</li>
		<li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests/new'>Änderungsantrag erstellen</a>
		</li>
	<?php
	if ($currentUser->hasPrivilegeByName(Privilege::MASTERDATAADMIN)) {
	?>
        <li class='nav-item'><a class='nav-link text-light'
			href='<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests/process'>Änderungsanträge bearbeiten</a>
		</li>
	<?php
	}
}

function middle_navigation ($currentUser){
	
}

function right_navigation ($currentUser){

}
