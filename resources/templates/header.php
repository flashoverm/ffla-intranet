<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="de">
<?php include_once 'head.php';?>


<body>
	<header>
	<div class="jumbotron py-3">
		   <div class="alert alert-dark">
	   		<strong>Testbetrieb</strong><br/>Das Portal befindet sich noch in der Erprobung. Fehler im Ablauf können nicht ausgeschlossen werden!
	   </div>
		<div class="row">
			<div class="col">
				<a href="<?= $config["urls"]["base_url"]?>">
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
	
	<?php 
	if(isset($app) && file_exists(TEMPLATES_PATH . "/" . $app . "/nav.php")){
		require_once (TEMPLATES_PATH . "/" . $app . "/nav.php");
	} else {
	?>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark"></nav>
	<?php 		
	}
	?>

	
	</header>