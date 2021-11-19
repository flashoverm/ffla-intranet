<div class="table-responsive">
	<table class="table table-hover table-striped" data-toggle="table" data-pagination="true" data-search="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Datum</th>
				<th data-sortable="true" class="text-center">Beginn</th>
				<th data-sortable="true" class="text-center">Ende</th>
				<th data-sortable="true" class="text-center">Einsatz</th>
				<?php if(!empty($options['showUserData'])){ ?>
					<th data-sortable="true" class="text-center">Antragsteller</th>
					<th data-sortable="true" class="text-center">Löschzug</th>
				<?php } ?>
				<?php if(!empty($options['showReason'])){ ?>
					<th data-sortable="true" class="text-center">Grund für Ablehnung</th>
				<?php } ?>
				<?php if(!empty($options['showLastUpdate'])){ ?>
					<th data-sortable="true" class="text-center">Geändert</th>
				<?php } ?>
				<?php if(!empty($options['showUserOptions'])){ ?>
					<th></th>
					<th></th>
				<?php } ?>
				<?php if(!empty($options['showAdminOptions']) || !empty($options['showViewConfirmation'])){ ?>
					<th></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $data as $confirmation ) {
			render(TEMPLATES_PATH . "/employerapp/elements/confirmation_row.php", $confirmation, $options);
		}
		?>
		</tbody>
	</table>
</div>