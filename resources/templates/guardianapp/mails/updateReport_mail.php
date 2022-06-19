ein Wachbericht wurde aktualisiert und ist als Anhang verfügbar oder unter:

<?= $report_link ?>


Titel: <?= $report->getTitle() ?> 
Typ: <?= $report->getType()->getType() ?>

Löschzug: <?= $report->getEngine()->getName() ?>

Ersteller: <?= $report->getCreator()->getFullName() ?>
