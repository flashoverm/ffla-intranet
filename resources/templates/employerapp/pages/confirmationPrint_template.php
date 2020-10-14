<?php
if(isset($confirmation) && isset($user)){
?>

<div class="table-responsive" style="width: 19cm">
	<table class="table table-borderless">
		<tbody>
			<tr>
				<td colspan="2">
					<img class="float-right" style="width: 7.5cm" src="<?= $config["urls"]["intranet_home"] ?>/images/layout/logo_stadt.png"/>
				</td>
			</tr>
			<tr>
				<td>
					<small>Postanschrift: Stadt Landshut, 84026 Landshut, Gz.: 5.697</small><br>
					<br>
					<?php
					if( ! empty ($user->employer_address) ){
						echo nl2br($user->employer_address);
					}
					?>
				</td>
				<td style="width: 22%">
					<b>Referat 5</b><br>
					<b>Feuerwehr</b><br>
					<small>
						<br>
						Niedermayerstarße 6<br>
						84028 Landshut<br>
						<br>
						Tel: 0871/96577-200<br>
						Fax: 0871/96577-205<br>				
						<br>
						feuerwehr@landshut.de<br>
						www.landshut.de
					</small>
				</td>
			</tr>
			<tr>
				<td colspan="2"><div class="mb-3"><b><?= $title ?></b></div></td>
			</tr>
			<tr>
				<td colspan="2">
					Sehr geehrte Damen und Herren,<br>
					<br>
					der/die Feuerwehrmann/-frau <?= $user->firstname . " " . $user->lastname ?><br>
					<br>
					war am <?= date($config ["formats"] ["date"], strtotime($confirmation->date)) ?> von <?= date($config ["formats"] ["time"], strtotime($confirmation->start_time)) ?> Uhr bis <?= date($config ["formats"] ["time"], strtotime($confirmation->end_time)) ?> Uhr<br>
					<br>
					für die Freiwillige Feuerwehr der Stadt Landshut im Einsatz.<br>
					<br>
					Die Richtigkeit der Angaben wir hiermit bestätigt.<br>
					<br>
					<br>
					<br>
					<small>Dieses Schreiben wurde maschinell erstellt und ist ohne Unterschrift gültig</small>
				</td> 
			</tr>
		</tbody>
	</table>
</div>
<?php 
}
?>
