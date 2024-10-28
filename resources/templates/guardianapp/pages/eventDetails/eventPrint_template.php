<?php 

if(isset($event)){
	$relevant = false;
	require_once 'eventTable.php'; ?>
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th>Wachlink</th>
					<th>Bericht erstellen</th>
				</tr>
	
				<tr>
					<td><img src="https://quickchart.io/qr?size=250&text=<?= urlencode($config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/view/".$event->getUuid()) ?>" title="Wachlink" /></td>
					<td><img src="https://quickchart.io/qr?size=250&text=<?= urlencode($config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/".$event->getUuid()) ?>" title="Wachlink" /></td>
				</tr>
				<tr>
					<td><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/view/<br>".$event->getUuid() ?></td>
					<td><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/<br>".$event->getUuid() ?></td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
<?php } ?>