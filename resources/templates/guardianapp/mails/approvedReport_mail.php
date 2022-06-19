ein Wachbericht wurde durch einen Wachbeauftragten überprüft und freigegeben.

Der Bericht befindet sich im Anhang oder unter: 

<?= $report_link ?>


Titel: <?= $report->getTitle() ?> 
Typ: <?= $report->getType()->getType() ?>

Löschzug: <?= $report->getEngine()->getName() ?>

Ersteller: <?= $report->getCreator()->getFullName() ?>
