<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<tbody>
		<?php
			foreach ( $privileges as $row ) {
		?>
			<tr>
				<td><?= $row->getPrivilege() ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href='<?= $config["urls"]["intranet_home"]?>/users/filter/<?= $row->getUuid() ?>'>
					Benutzer anzeigen
					</a>
			</tr>
		<?php
			}
		?>
		</tbody>
	</table>
</div>