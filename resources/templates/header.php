<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="de">
<?php include_once 'head.php';?>
<script>
	function isDateSupported() {
		var input = document.createElement('input');
		var value = 'a';
		input.setAttribute('type', 'date');
		input.setAttribute('value', value);
		return (input.value !== value);
	};
</script>

<body>
	<div id="overlay" style="display:inline;">
 		<div class="loader"></div>
 	</div>
	<header>
	<div class="jumbotron py-3">
		<!-- 
		<div class="alert alert-dark">
			<strong>Testbetrieb</strong><br/>Das Portal befindet sich noch in der Erprobung. Fehler im Ablauf können nicht ausgeschlossen werden!
		</div>
		 -->
		<div>
			<a href="<?= $config["urls"]["intranet_home"]?>/">
				<img class="d-block logo"
					src="<?= $config["urls"]["intranet_home"] ?>/images/layout/shortheader_new-1.png">
			</a>
		</div>
	</div>
	
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<button class="navbar-toggler" type="button" data-toggle="collapse"
				data-target="#navbarMainContent">
				<span class="navbar-toggler-icon"></span>
		</button>
		
		<?php 
		if(isset($app) && file_exists(TEMPLATES_PATH . "/" . $app . "/nav.php")){
			require_once (TEMPLATES_PATH . "/" . $app . "/nav.php");
		}
		?>
		
		<div class='collapse navbar-collapse' id='navbarMainContent'>
			<ul class='navbar-nav mr-auto'>
				<li class='nav-item dropdown'>
					<a class='nav-link dropdown-toggle text-light' data-toggle='dropdown' href='#'>Home</a>
        			<div class='dropdown-menu bg-dark'>
						<a class='dropdown-item text-light' href='<?= $config["urls"]["guardianapp_home"] ?>/'>Wachverwaltung</a>
						<a class='dropdown-item text-light' href='<?= $config ["urls"] ["hydrantapp_home"] ?>/'>Hydrantenverwaltung</a>
						<a class='dropdown-item text-light' href='<?= $config ["urls"] ["employerapp_home"] ?>/'>Arbeitgeberbestätigungen</a>
						<a class='dropdown-item text-light' href='<?= $config ["urls"] ["masterdataapp_home"] ?>/'>Stammdatenänderung</a>
						<a class='dropdown-item text-light' href='<?= $config ["urls"] ["filesapp_home"] ?>/'>Formulare</a>
						<a class='dropdown-item text-light' href='<?= $config ["urls"] ["pagerapp_home"] ?>/'>Alarmierungsauswertung</a>
					</div>
				</li>
			    <?php 
				if(isset($app) && file_exists(TEMPLATES_PATH . "/" . $app . "/nav.php")){
					left_navigation($currentUser);
				}
				?>
	        </ul>
		
			<ul class='navbar-nav mr-auto'>
			    <?php 
				if(isset($app) && file_exists(TEMPLATES_PATH . "/" . $app . "/nav.php")){
					middle_navigation($currentUser);
				}
				?>
	        </ul>
		
			<ul class="navbar-nav ml-auto">
				<?php 
				if(isset($app) && file_exists(TEMPLATES_PATH . "/" . $app . "/nav.php")){
					right_navigation($currentUser);
				}
				?>
				<?php 
				if($currentUser){

					if($currentUser->hasPrivilegeByName(Privilege::PORTALADMIN)){
					?>
						<li class='nav-item dropdown'>
							<a class='nav-link dropdown-toggle text-light' data-toggle='dropdown' href='#'>Portaladministration</a>
        					<div class='dropdown-menu bg-dark'>
								<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/users/overview'>Benutzerverwaltung</a>
								<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/users/admin'>Benutzer anlegen</a>
								<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/users/import'>Benutzer importieren</a>
								<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/users/privileges'>Berechtigungen</a>
								<div class='dropdown-divider'></div>
								<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/administration/logbook'>Logbuch</a>
								<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/administration/maillog'>Mail Logbuch</a>
							</div>
						</li>
					<?php 
					}
					?>
					<li class='nav-item dropdown'>
						<?php if($currentUser->getEngine()->getUuid() == $currentUser->getMainEngine()->getUuid()) {
							$displayname = $currentUser->getEmail();
						} else {
							$displayname = $currentUser->getEmail() . " (" . $currentUser->getEngine()->getShortName() . ")";
						}
						?>
						<a class='nav-link dropdown-toggle text-light' data-toggle='dropdown' href='#'><?= $displayname ?></a>
			        	<div class='dropdown-menu dropdown-menu-right bg-dark'>
							<a class='dropdown-item disabled text-secondary'><?= $currentUser->getEngine()->getName() ?></a>
							<?php
							if( count($currentUser->getAdditionalEngines()) > 0 ){
							?>
								<form action="<?= $config["urls"]["intranet_home"] ?>/setView" method='POST'>
									<input type="hidden" id="ref" name="ref" value="<?= $_SERVER['REQUEST_URI'] ?>">
									<input type="submit" class='dropdown-item text-light' value="Ansicht wechseln">
								</form>
							<?php
							}
							?>
							<div class='dropdown-divider'></div>
							<?php
							if( $currentUser->hasPrivilegeByName(Privilege::EDITUSER)) {
							?>
								<a class='dropdown-item text-light' href='<?= $config["urls"]["intranet_home"] ?>/users/edit'>Benutzer bearbeiten</a>
								<a class='dropdown-item text-light' href='<?= $config["urls"]["intranet_home"] ?>/users/password/change'>Passwort ändern</a>
								<a class='dropdown-item text-light' href='<?= $config["urls"]["intranet_home"] ?>/users/settings'>Einstellungen</a>
							<?php
							}
							?>
					       	<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/logout'>Abmelden</a>
						</div>
					</li>
				<?php
				} else {
				?>
					<li class='nav-item'>
		                <a class='nav-link text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/login'>Anmelden</a>
		            </li>
	            	<?php
	                if ($config ["settings"] ["selfregistration"]) {
	                	echo " 	<li class='nav-item'>
	                		<a class='nav-link text-light' href='" . $config["urls"]["intranet_home"]. "/users/register'>Registrierung</a>
	            			</li>";
	                }
				}
				?>
			</ul>
		</div>
	</nav>
</header>
					