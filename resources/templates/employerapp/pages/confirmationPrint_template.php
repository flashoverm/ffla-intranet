<?php
if(isset($confirmation)){
?>

<div class="table-responsive">
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
					if( ! empty ($confirmation->getUser()->getEmployerAddress()) ){
						echo nl2br($confirmation->getUser()->getEmployerAddress());
					}
					?>
				</td>
				<td style="width: 22%">
					<b>Referat 5</b><br>
					<b>Feuerwehr</b><br>
					<small>
						<br>
						Niedermayerstraße 6<br>
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
				<td colspan="1">
					Sehr geehrte Damen und Herren,<br>
					<br>
					der/die Feuerwehrmann/-frau <?= $confirmation->getUser()->getFullName() ?><br>
					<br>
					war am <?= date($config ["formats"] ["date"], strtotime($confirmation->getDate())) ?> von <?= date($config ["formats"] ["time"], strtotime($confirmation->getStartTime())) ?> Uhr bis <?= date($config ["formats"] ["time"], strtotime($confirmation->getEndTime())) ?> Uhr<br>
					<br>
					für die Freiwillige Feuerwehr der Stadt Landshut im Einsatz.<br>
					<br>
					Die Richtigkeit der Angaben wird hiermit bestätigt.<br>
					<br>
					<br>
					<br>
					<p align='justify'>Die Arbeitgeberbestätigung <u><b>muss</b></u> dem Antrag auf Erstattung fortgewährter Leistungen beigelegt werden. Eine Abrechnung / Auszahlung ohne Nachweis kann von der Buchhaltung nicht durchgeführt werden.</p>
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
