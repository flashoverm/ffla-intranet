<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="de">
<?php 
	include_once 'head.php';
	if( isset($orientation) && $orientation == 'landscape' ) {
		setPrintToLandscape();
	}
?>
<body class='page <?php if( isset($orientation) ) { echo $orientation; } ?>' style="margin-bottom: 0px;" >
	<?php
	if(! $noHeader ){
	?>
 	<header>
	 	<div class="row">
			<div class="col">
				<img class="img-fluid d-block" style="height:70px; margin-bottom:1rem" src="<?= $config["urls"]["intranet_home"] ?>/images/layout/logo_bw.png">
			</div>
		
    		<div class="col d-flex align-items-center justify-content-center">
    				<h2><?= $title ?></h2>
    		</div>
    		<div class="col">
			</div>
    	</div>
 	</header>
	<?php 
	}
	?>
