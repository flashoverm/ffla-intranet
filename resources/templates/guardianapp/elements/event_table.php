	<div class="table-responsive">
		<table class="table table-hover table-striped" data-toggle="table" data-pagination="true" data-search="true">
			<thead>
				<tr>
					<th data-sortable="true" class="text-center">Datum</th>
					<th data-sortable="true" class="text-center">Beginn</th>
					<th data-sortable="true" class="text-center">Ende</th>
					<th data-sortable="true" class="text-center">Typ</th>
					<th data-sortable="true" class="text-center">Titel</th>
					<th data-sortable="true" class="text-center">Zuständig</th>
					<?php 
					if(!empty($options['showOccupation'])){
					?>
						<th data-sortable="true" class="text-center">Belegung</th>
					<?php 
					}
					if(!empty($options['showPublic'])){
					?>
					<th data-sortable="true" class="text-center">Öffentlich</th>
					<?php
					}
					?>
					<th></th>
					<?php
					if( !empty($options['showDelete']) &&
							$guardianUserController->isUserAllowedToEditSomeEvent($currentUser)){
						echo"<th></th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
			<?php 
			foreach ( $data as $event ) {
				renderEventRow($event, $options);
			}
			?>
			</tbody>
		</table>
	</div>