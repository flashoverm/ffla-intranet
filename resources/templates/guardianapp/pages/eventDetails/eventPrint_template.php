<?php 

if(isset($event)){
	$relevant = false;
	require_once 'eventTable.php'; ?>
			</tbody>
		</table>
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th>Wachlink</th>
					<th>Bericht erstellen</th>
				</tr>
	
				<tr>
					<td><img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=<?= urlencode($config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/".$event->getUuid()) ?>&choe=UTF-8" title="Wachlink" /></td>
					<td><img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=<?= urlencode($config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/".$event->getUuid()) ?>&choe=UTF-8" title="Wachlink" /></td>
				</tr>
				<tr>
					<td><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/<br>".$event->getUuid() ?></td>
					<td><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/<br>".$event->getUuid() ?></td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
<?php } ?>