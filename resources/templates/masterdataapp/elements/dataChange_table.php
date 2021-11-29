<div class="table-responsive">
	<table class="table table-hover table-striped" data-toggle="table" data-pagination="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center" >Erstellt</th>
				<th data-sortable="true" class="text-center" >Typ</th>
				<th data-sortable="true" class="text-center" >Neuer Wert</th>
				<th data-sortable="true" class="text-center" >Änderung bei</th>
				<?php if(!empty($options['showUserData'])){ ?>
					<th data-sortable="true" class="text-center">Antragsteller</th>
					<th data-sortable="true" class="text-center">Löschzug</th>
				<?php } ?>
				<?php if(!empty($options['showLastUpdate'])){ ?>
					<th data-sortable="true" class="text-center">Geändert</th>
				<?php } ?>
				<th class="text-center">
					Anmerkungen<?php if(!empty($options['showRequest'])){
						echo "/Rückfrage";
					}?>
				</th>
				<?php if(!empty($options['showUserOptions'])){ ?>
					<th></th>
					<th></th>
				<?php } ?>
				<?php if(!empty($options['showAdminOptions'])){ ?>
					<th></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $data as $dataChangeRequest ) {
			render(TEMPLATES_PATH . "/masterdataapp/elements/dataChange_row.php", $dataChangeRequest, $options);
		}
		?>
		</tbody>
	</table>
</div>