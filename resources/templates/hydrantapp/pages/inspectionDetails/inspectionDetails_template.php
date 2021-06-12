<?php require_once 'inspectionTable.php';?>

<div id="buttons">
	<a class="btn btn-outline-primary" href="<?= $config ["urls"] ["hydrantapp_home"] ?>/inspection">Zurück</a>
	<div class="float-right">
		<a class="btn btn-primary" target="_blank" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/". $inspection->getUuid() . "/file&force=true"; ?>">PDF neu erzeugen</a>
    	<a class="btn btn-primary" target="_blank" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/". $inspection->getUuid() . "/file"; ?>">PDF anzeigen</a>
    	<a class="btn btn-primary" href="<?= $config ["urls"] ["hydrantapp_home"] ?>/inspection/edit/<?= $inspection->getUuid() ?>">Bearbeiten</a> 
	</div>   
</div>
