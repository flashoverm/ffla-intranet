<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<tbody>
		<?php
		  if(isset($engineView) && $engineView){
		      $baseUrl = $config["urls"]["intranet_home"] . "/users/engine/filter/";
		  } else {
		      $baseUrl = $config["urls"]["intranet_home"] . "/users/filter/";
		  }
		  foreach ( $privileges as $row ) {
		?>
			<tr>
				<td><?= $row->getPrivilege() ?> - <?= $row->getDescription() ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href='<?= $baseUrl . $row->getUuid() ?>'>
					Benutzer anzeigen
					</a>
				</td>
			</tr>
		<?php
			}
		?>
		</tbody>
	</table>
</div>