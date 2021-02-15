<form action="" method="post">

	<div class="card mx-auto mb-2">
		<div class="card-body">
			<h5 class="card-title"><?= $currentUser->getEngine()->getName() ?></h5>
			<a href='<?= $config["urls"]["intranet_home"] ?>/setView/<?= $currentUser->getEngine()->getUuid() ?>'
				class="card-link">Weiter als <?= $currentUser->getEngine()->getName() ?></a>
		</div>
	</div>
	
	<?php
	foreach($additionalEngines as $engine){
	?>
		<div class="card mx-auto mb-2">
			<div class="card-body">
				<h5 class="card-title"><?= $currentUser->getEngine()->getName() ?></h5>
				<a href='<?= $config["urls"]["intranet_home"] ?>/setView/<?= $engine->getUuid() ?>'
					class="card-link">Weiter als <?= $engine->getName() ?></a>
			</div>
		</div>
	<?php
	}
	?>
</form>