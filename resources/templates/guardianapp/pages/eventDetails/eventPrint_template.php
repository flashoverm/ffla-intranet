<?php 
require_once realpath(dirname(__FILE__) . "/../../../../config.php");

if(isset($event)){
	
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
					<td><img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=<?= urlencode($config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/".$event->uuid) ?>&choe=UTF-8" title="Wachlink" /></td>
					<td><img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=<?= urlencode($config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/".$event->uuid) ?>&choe=UTF-8" title="Wachlink" /></td>
				</tr>
				<tr>
					<td><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/<br>".$event->uuid ?></td>
					<td><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/<br>".$event->uuid ?></td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
<?php } ?>