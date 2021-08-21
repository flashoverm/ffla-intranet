<div class="card" style="width: 25rem;" id="contents">
	<div class="card-body">
		<h3>Inhalt</h3>
		<ul>
			<li><a href="#reach">Erreichen der Wachverwaltung</a></li>
		</ul>
		<h5>Funktionen für alle Nutzer</h5>
		<ul>
			<li><a href="#subscribe">In Wache einschreiben</a></li>
			<li><a href="#unsubscribe">Aus Wache austragen</a></li>
			<li><a href="#acknowledge">Einteilung zur Kentniss nehmen</a></li>
			<li><a href="#report">Wachbericht erstellen</a></li>
		</ul>
		<h5>Funktionen für Wachbeauftragte</h5>
		<ul>
			<li><a href="#event">Wache anlegen</a></li>
			<li><a href="#eventOrg">Eingestellte Wachen verwalten</a></li>
			<li><a href="#edit">Wache bearbeiten</a></li>
			<li><a href="#warn">Warnfunktion</a></li>
			<li><a href="#reportOrg">Wachberichte verwalten</a></li>
		</ul>
	</div>
</div>

<div class="mt-5" id="reach">
	<h4>
		Erreichen der Wachverwaltung <a href="#">&uarr;</a>
	</h4>
	<p>Über intranet.feuerwehr-landshut.de erreicht man die Landing-Page.
		Von hier gelangt man entweder zur Wachverwaltung oder zu den
		Anwendungen.</p>
		
</div>

<div class="mt-5">
	<h3>Funktionen für alle Nutzer</h3>

	<div class="mt-5" id="subscribe">
		<h4>
			In Wache einschreiben <a href="#">&uarr;</a>
		</h4>
		<p>Möchte man an einer Wache teilnehmen, gibt es zwei Möglichkeiten:</p>
		<ul>
			<li>Man erhält von einem Wachbeauftragten eine E-Mail mit einem Link zur Wache. Über diesen Link gelangt man direkt zur Detail-Seite der Wache.</li>
			<li>Man informiert sich über Wachen direkt im Portal. Die Liste der Wachen erreicht man als der Startseite der Wachverwaltung oder unter „Wachen“ - „Wachübersicht“.</li>
		</ul>
		
		<p>Man kann außerdem durch einen Wachbeauftragen zu einer Wache eingeteilt werden. Darüber wird man per E-Mail informiert.</p>			

		<p>Auf der Detailseite der Wache werden, neben den allgemeinen Informationen zur Wache, die zu besetzenden Positionen angezeigt. Bei bereits besetzten Positionen wird Name und Löschzug der jeweiligen Person angezeigt.</p>

		<p>Des Weiteren kann der Link zur Wache kopiert werden oder mit „Kalendereintrag“ eine Datei zum Import des Termins in Outlook heruntergeladen werden.</p>
		
		<p>Die Druckansicht enthält neben den Wachdaten zwei QR-Codes. Einmal für den Link zur Wache und einmal für den bereits vorausgefüllten Wachbericht</p>

		<img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="<?= $config["urls"]["intranet_home"] ?>/images/manual/guardian/Event_Detail.jpg" style="width: 75%;">

		<p>Bei freien Positionen kann man sich mit „Eintragen“ für die Wache einschreiben, sofern die Voraussetzung für die Position erfüllt ist (z.B. Atemschutzgeräteträger).</p>
		<p>Möchte man eine E-Mail über die Eintragung erhalten, kann man dies auswählen. Die Wachbeauftragten werden in jeden Fall informiert.</p>

		<img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="<?= $config["urls"]["intranet_home"] ?>/images/manual/guardian/Event_Subscribe.jpg" style="width: 75%;">
			
		<p>Wachbeauftrage können festlegen, ob ein Teilnehmer einer Wache erst bestätigt werden muss, bevor er an der Wache teilnehmen kann. Ist dies der Fall, erhält man nach der Eintragung per Mail den Hinweis, dass eine Bestätigung ausstehend ist.</p>
		<p>Sobald die Wachteilnahme durch den Wachbeauftragten freigegeben wurde, erhält man erneut eine Bestätigung per Mail. Erst dann ist die Teilnahme an der Wachveranstaltung möglich.</p>

	</div>
	
	<div class="mt-5" id="unsubscribe">
		<h4>
			Aus Wache austragen <a href="#">&uarr;</a>
		</h4>
		
		<p>Auf der Detailseite einer Wache kann man sich, nachdem man sich selbst eingetragen hat oder in eine Wache eingeteilt wurde, wieder austragen.</p>
		<p>Die Wachbeauftragten werden darüber per E-Mail in Kentniss gesetzt.</p>
		
		<img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="<?= $config["urls"]["intranet_home"] ?>/images/manual/guardian/Event_Unsubscribe.jpg" style="width: 75%;">
	</div>
	
	<div class="mt-5" id="acknowledge">
		<h4>
			Einteilung zur Kentniss nehmen<a href="#">&uarr;</a>
		</h4>
		
		<p>Wird man für eine Wache eingeteilt, kann man auf der Detailseite der Wache die Einteilung zur Kentniss nehmen.</p>
		<p>Das hilft dem Wachbeauftragten dabei, sicherzustellen das das eingeteilte Person weiß, dass es an der Wache teilnehmen soll.</p>
	</div>
	
	<div class="mt-5" id="report">
		<h4>
			Wachbericht erstellen <a href="#">&uarr;</a>
		</h4>
		
		<p>Möchte man einen Wachbericht erstellen, gibt es zwei Möglichkeiten:</p>
		<ul>
			<li>Das Formular für den Wachbericht erreicht man von der Startseite der Wachverwaltung unter „Wachberichte“ - „Bericht anlegen“.</li>
			<li>Auf der Detailseite der Wache befindet sich ein Link zu einem vorausgefüllten Bericht. Die bekannten Daten werden von der Wache in das Berichtsformular übernommen (und können ggf. angepasst werden)</li>
		</ul>

		<p>Besonderheiten bei der Berichterstellung:</p>
		<ul>
			<li>Wählt man als Typ „Sonstige Wache“ aus, muss der Typ in einem
				eigenen Feld näher definiert werden</li>
			<li>Mit „Zuständiger Löschzug“ weißt man den Bericht einem Löschzug
				bzw. der Verwaltung (Geschäftszimmer) zu (z.B. bei Schülerwachen)</li>
			<li>Wurde für die Wache ein Eintrag durch die ILS angelegt, kann auch
				dies durch setzen des Häkchens erfasst werden</li>
		</ul>

		<p>Wurden die Daten erfasst oder von der Wache übernommen, muss noch das Wachpersonal hinzugefügt werden. Dazu hat man zwei Möglichkeiten:</p>
		<ul>
			<li>Das Wachpersonal hat die Wache mit einem Fahrzeug absolviert</li>
			<li>Die Wache fand ohne Fahrzeug statt (Regelfall bei Theaterwachen)</li>
		</ul>

		<p>In jedem Fall sind die Personen mit Namen, jeweiliger Position und
			Löschzug zu erfassen. Mehr Positionen können über das „+“ hinzugefügt
			bzw. mit „–“ wieder entfernt werden. Datum und Uhrzeiten werden vom
			Hauptformular übernommen, können aber bei Abweichungen angepasst
			werden.</p>
		<p>Bei Wachen mit Fahrzeug ist das Personal pro Fahrzeug zu erfassen,
			dabei muss neben der Bezeichnung des Fahrzeugs auch die gefahrenen
			Kilometer eingeben werden.</p>
		<p>Fahrzeug bzw. Personal wird an den Bericht angefügt und kann durch
			„Entfernen“ wieder entfernt werden.</p>
		<p>Der Bericht wird gespeichert und kann von der Verwaltung
			(Geschäftszimmer) und vom Wachbeauftragen des zuständigen Zugs
			eingesehen werden. Letzterer erhält den Bericht auch per E-Mail
			zugesandt.</p>

		<img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="<?= $config["urls"]["intranet_home"] ?>/images/manual/guardian/Report_Create.jpg" style="width: 75%;"> <img
			class="img-fluid rounded mb-2 mx-auto d-block border"
			src="<?= $config["urls"]["intranet_home"] ?>/images/manual/guardian/Report_AddVehicle.jpg" style="width: 75%;">

	</div>
</div>

<div class="mt-5">
	<h3>
		Funktionen für Wachbeauftragte <a href="#">&uarr;</a>
	</h3>
	<div class="mt-5" id="access">
		<p>Wachbeauftrage haben zusätzliche Rechte im Portal. Diese Rechte müssen von einem Administrator freigeschaltet werden.
	</div>

	<div class="mt-5" id="event">
		<h4>
			Wache anlegen <a href="#">&uarr;</a>
		</h4>
		<p>Unter „Wachen“ kann man mit „Wache anlegen“ eine neue Wache erfassen.</p>
		<p>Hier sind die entsprechenden Daten zu erfassen.</p>
		<p>Besonderheiten:</p>
		<ul>
			<li>Wählt man als Typ „Sonstige Wache“ aus, muss der Typ in einem
				eigenen Feld näher definiert werden
			
			<li>Mit „Zuständiger Löschzug“ weißt man die Wache dem Löschzug zu,
				der sie besetzen soll (bei Schülerwachen ist die Verwaltung
				(Geschäftszimmer) zu verwenden). Standardmäßig wieder hier der
				eigene Löschzug ausgewählt.
			
			<li>Die Felder „Ende“ und „Titel“ sind optional</li>
			<li>Über den Button „Personalvorschlag laden“ kann, sofern vorhanden,
				ein Vorschlag für die Besetzung geladen werden. Dabei werden alle
				Einträge überschrieben!</li>
		</ul>

		<p>Am Ende des Formulars können die Positionen, die besetzt werden
			müssen, erfasst werden. Mehr Positionen können über das „+“
			hinzugefügt bzw. mit „X“ wieder entfernt werden.</p>
		
		<p>Wird „Personal muss bestätigt werden“ ausgewählt, so erhalten die Wachteilnehmer nach Eintragung in diese Wache nur eine vorläufige Bestätigung. Der Wachbeauftrage muss die Teilnahme bestätigen, erst dann soll die Person an der Wachveranstaltung teilnehmen.
		
		<p id="publish">Veröffentlicht man die Wache, soll nicht nur die Mannschaft des eigenen Zuges die Wache absolvieren können, sondern auch Personen anderer Züge. Genau bedeutet dies, dass die Wache für jeden sichtbar ist und sich jeder eintragen
			kann. Des Weiteren werden alle Wachbeauftragten per E-Mail über die
			Wache informiert, welche den Link wiederum an die Mannschaft „seines“
			Zugs weiterleiten kann.</p>
		<p>
			Will man Positionen bereits mit bestimmten Personen besetzen, kann
			man dies über die Detailseite der Wache in Nachgang vornehmen (siehe
			<a href="#eventOrg">„Eingestellte Wachen verwalten“</a>).
		</p>
		

		<img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="<?= $config["urls"]["intranet_home"] ?>/images/manual/guardian/Event_Create.jpg" style="width: 75%;">

	</div>

	<div class="mt-5" id="eventOrg">
		<h4>
			Eingestellte Wachen verwalten <a href="#">&uarr;</a>
		</h4>
		<p>Unter „Wachen“ – „Wachübersicht“ erhält man eine Übersicht über
			alle Wachen, die</p>
		<ul>
			<li>man selbst angelegt hat</li>
			<li>dem eigenen Zug zugewiesen wurden</li>
			<li>von anderen Zügen veröffentlicht wurden (bei diesen besitzt man nicht die Funktionen des Wachbeauftragen)</li>
		</ul>

		<p>In der Übersicht sieht man die allgemeinen Daten, die Belegung
			(Rot, wenn noch Positionen offen sind; grün, sobald die Wache voll
			besetzt ist) und ob die Wache öffentlich ist.</p>
			
		<p>Hier können Wachen auch gelöscht werden. Dabei öffnet sich ein
			Bestätigungsfenster, um versehentliches Löschen zu verhindern.</p>
		<p>Über „Details“ gelangt man zur Detailseite der Wache in der
			„Beauftragten-Ansicht“.</p>
		<p>
			Diese ist zum größten Teil identisch mit der Ansicht für alle
			Benutzer (siehe <a href="#subscribe">„In Wache einschreiben“</a>).
			Natürlich hat man auch als Wachbeauftragter die Möglichkeit sich für
			die Wache einzuschreiben (bzw. sich selbst einzuteilen).
		</p>
		
		<p>Über „Eintragen“ kann man auch andere Personen, die man der Wache
			bzw. einer Position bereits zuweisen will, eintragen (Eingabe der Daten per Hand oder Auswahl über die Dropdown-Liste). Diese Person wird dann
			ebenfalls per E-Mail informiert, dass sie eingetragen wurde.</p>
			
		<p>Als Wachbeauftragter kann man
			<ul>
				<li>eingeschriebene Personen mit „Austragen“ aus der Wache entfernen</li>
				<li>mit „Bestätigen“ die Person informieren, dass die Teilnahme gestattet ist</li>
				<li>einsehen, welche Personen die Wache zur Kentniss genommen haben</li>
			</ul>
		<p>Des Weiteren hat man die Möglichkeit, noch nicht veröffentlichte
			Wachen im Nachhinein zu veröffentlichen.</p>
			
		<p>Bereits stattgefundene Wachen können mit Klick auf „Vergangene
			Wachen“ angezeigt werden (Wird nur angezeigt, wenn Wachen
			stattgefunden haben). Hier sind aber keine Änderungen (Löschen,
			Austragen, Veröffentlichen) mehr möglich.</p>

		<img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="<?= $config["urls"]["intranet_home"] ?>/images/manual/guardian/Event_Overview.jpg" style="width: 75%;">

	</div>

	<div class="mt-5" id="edit">
		<h4>
			Wache bearbeiten <a href="#">&uarr;</a>
		</h4>
		<p>In der Detailansicht kann der Ersteller der Wache diese Bearbeiten.
			Es können alle Felder und die nötigen Funktionen angepasst werden.</p>

		<p>Beim Laden des Personalvorschlags werden alle Einträge
			überschrieben. Eingetragene Personen werden entfernt!</p>

		<p>Am Ende des Forumlars besteht die Möglichkeit, alle eingetragenen
			Personen, sofern vorhanden, über die Änderungen zu informieren.
			Wurden Funktionen entfernt, bei denen eine Person eingetragen war,
			wird diese in jedem Fall benachrichtigt.</p>
	</div>

	<div class="mt-5" id="warn">
		<h4>
			Warnfunktion <a href="#">&uarr;</a>
		</h4>
		<p>Die Anwendung verfügt über eine Warnfunktion, die den Erstelle der
			Wache 10 Tage vor der Veranstaltung per E-Mail informiert, wenn noch
			nicht alle Positionen besetzt wurden.</p>
	</div>

	<div class="mt-5" id="reportOrg">
		<h4>
			Wachberichte verwalten <a href="#">&uarr;</a>
		</h4>
		<p>Unter „Wachberichte“ – „Berichtsübersicht“ erhält man eine
			Übersicht über alle Wachberichte, die dem eigenen Zug zugewiesen
			wurden.</p>
			
		<p>Benutzer, die der Verwaltung (Geschäftszimmer) zugewiesen wurde,
			sehen die Berichte aller Züge.</p>
			
		<p>Auf der Berichtsseite sieht man den ausgefüllten Bericht.</p>
		
		<p>Dort hat man diverse „Berichts-Optionen“:</p>
		
			<ul><li>Bericht als PDF anzeigen</li></ul>
		
			<ul><li>PDF neu erzeugen</li>
			<small>z.B. wenn das PDF fehlerhaft ist</small>
			</ul>
		
			<ul><li>EMS</li>
			<small>Diese Funktion dient als Hilfe/Übersicht für Wachbeauftragte. Damit kann ein Bericht markiert werden, der in EMS eingetragen wurde (Markierung kann wieder entfernt werden)</small>
			</ul>
			
			<ul><li>Bericht freigeben</li>
			<small>Ein Wachbeauftragter gibt einen Bericht freigeben, wenn seine Korrektheit überprüft wurde. Erst dann erfolgt die Abrechnung durch die Feuerwehrverwaltung. Die Freigabe kann auch wieder entfernt werden (nur im Fall eines Irtums sinnvoll!)</small>
			</ul>
			
			<ul><li>Bericht berbeiten</li>
			<small>Bearbeitung des Berichts (analog zu <a href="#report">„Wachbericht erstellen“</a>)</small>
			</ul>
		
			<ul><li>Bericht löschen</li></ul>

		<img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="<?= $config["urls"]["intranet_home"] ?>/images/manual/guardian/Report_Overview.jpg" style="width: 75%;">

	</div>
</div>

<div class="mt-5">
	<h4>
		<a href="#">&uarr;</a>
	</h4>
	<p>Stand 21.08.2021</p>
</div>