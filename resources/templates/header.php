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
		   <div class="alert alert-dark">
	   		<strong>Testbetrieb</strong><br/>Das Portal befindet sich noch in der Erprobung. Fehler im Ablauf können nicht ausgeschlossen werden!
	   </div>
		<div class="row">
			<div class="col">
				<a href="<?= $config["urls"]["intranet_home"]?>/">
					<img class="img-fluid d-block"
						src="<?= $config["urls"]["intranet_home"] ?>/images/layout/shortheader_new-1.png">
				</a>
					 
			</div>
			<div class="col my-auto">
				<h1 class="text-center"><?= $title ?></h1>
				<?php
		          if(isset($subtitle)){
			         echo "<h5 class='text-center'>".$subtitle."</h5>";
		          }
		        ?>
			</div>
			<div class="col">
			</div>
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
						<a class='dropdown-item text-light' href='<?= $config ["urls"] ["filesapp_home"] ?>/'>Formulare</a>
					</div>
				</li>
			    <?php 
				if(isset($app) && file_exists(TEMPLATES_PATH . "/" . $app . "/nav.php")){
					left_navigation($loggedIn);
				}
				?>
	        </ul>
		
			<ul class='navbar-nav mr-auto'>
			    <?php 
				if(isset($app) && file_exists(TEMPLATES_PATH . "/" . $app . "/nav.php")){
					middle_navigation($loggedIn);
				}
				?>
	        </ul>
		
			<ul class="navbar-nav ml-auto">
				<?php 
				if(isset($app) && file_exists(TEMPLATES_PATH . "/" . $app . "/nav.php")){
					right_navigation($loggedIn);
				}
				?>
				<?php 
				if($loggedIn){
					
					if(current_user_has_privilege(PORTALADMIN)){
					?>
						<li class='nav-item dropdown'>
							<a class='nav-link dropdown-toggle text-light' data-toggle='dropdown' href='#'>Portaladministration</a>
        					<div class='dropdown-menu bg-dark'>
								<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/users'>Benutzerverwaltung</a>
								<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/users/new'>Benutzer anlegen</a>
								<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/privilege'>Rechteverwaltung</a>
								<div class='dropdown-divider'></div>
								<a class='dropdown-item text-light' href='<?= $config ["urls"] ["intranet_home"] ?>/maillog'>Mail Logbuch</a>
							</div>
						</li>
					<?php 
					}
					?>
					<li class='nav-item dropdown'>
						<a class='nav-link dropdown-toggle text-light' data-toggle='dropdown' href='#'><?= $_SESSION ['intranet_email'] ?></a>
			        	<div class='dropdown-menu dropdown-menu-right bg-dark'>
							<a class='dropdown-item disabled text-secondary'><?= get_engine_obj_of_user($_SESSION ['intranet_userid'])->name ?></a>
							<div class='dropdown-divider'></div>
							<a class='dropdown-item text-light' href='<?= $config["urls"]["intranet_home"] ?>/change_password'>Passwort ändern</a>
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
	                		<a class='nav-link text-light' href='" . $config["urls"]["intranet_home"]. "/users/new'>Registrierung</a>
	            			</li>";
	                }
				}
				?>
			</ul>
		</div>
	</nav>
</header>