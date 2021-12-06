<?php
global $config;
$random = random_int(10000, 100000);
?>
<tr data-toggle="collapse" data-target="#collapseme<?= $random ?>">
	<td><?= date($config ["formats"] ["datetime"] . ":s", strtotime($data->getTimestamp())); ?></td>
	<td><?= $data->getRecipient() ?></td>
	<td><?= $data->getSubject() ?></td>
	<td><?= MailLog::MAILLOG_STATES[$data->getState()] ?></td>
</tr>
<tr class="d-none"></tr><!-- Empty row to apply correct stripe pattern -->
<tr>
	<td class="collapse-td" colspan="5">
		<div class="collapse" id="collapseme<?= $random ?>">
			<div class="collapse-content">
				<?php 
				$body = nl2br($data->getBody());
					
				if($data->getError() != NULL){
					$body = $body . "<br><br><br><b>Fehlermeldung:</b><br>" . $data->getError();
					}
				echo $body;
				?>
			</div>
		</div>
	</td>
</tr>