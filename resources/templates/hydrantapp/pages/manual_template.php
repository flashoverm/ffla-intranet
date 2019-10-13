<div class="card" style="width: 25rem;" id="contents">
	<div class="card-body">
		<h3>Inhalt</h3>
		<ul>
			<li><a href="#reach">Erreichen der Hydrantenverwaltung</a></li>
		</ul>
		<h5>Funktionen für alle Nutzer</h5>
		<ul>
			<li><a href="#maps">Hydrantenkarten anzeigen</a></li>
		</ul>
		<h5>Funktionen für Hydrantenbeauftragte</h5>
		<ul>
			<li><a href="#checkup">Hydrantenprüfung erstellen</a></li>
			<li><a href="#checkupassist">Hydrantenprüfung mit Assistent erfassen</a></li>
			
			<li><a href="#checkupadmin">Prüfungsübersicht- und verwaltung</a></li>
			<li><a href="#checkupcandidates">Aktuelle Prüfobjekte</a></li>
		</ul>
	</div>
</div>

<div class="mt-5" id="reach">
	<h4>
		Erreichen der Hydrantenverwaltung <a href="#">&uarr;</a>
	</h4>
	<p>Über intranet.feuerwehr-landshut.de erreicht man die Landing-Page.
		Von hier gelangt man entweder zur Hydrantenverwaltung oder zu den
		anderen Anwendungen.</p>

	<p>Ein direkter Zugriff ist über
		intranet.feuerwehr-landshut.de/hydrants möglich.</p>
		
	<p>Für die Hydrantenverwaltung ist aus Datenschutzgründen eine Anmeldung erforderlich.</p>
</div>

<div class="mt-5">
	<h3>Funktionen für alle Nutzer</h3>

	<div class="mt-5" id="maps">
		<h4>
			Hydrantenkarten anzeigen <a href="#">&uarr;</a>
		</h4>
		<p>Es stehen verschiedene Hydrantekarten zur Verfügung</p>
		<ul>
			<li>Suche nach Hydranten-Nummer (Hy-Nummer): <br>Der jeweilige Hydrant wird auf einer detailierten Karte mit hoher Zoomstufe angezeigt, um diesen zu finden.</li>
			<li>Suche nach Straße: <br>Von dort aus kann die jeweilige Detail-Karte eines jeden Hydranten angezeigt werden. 
			Die Autovervollständigung hilft bei der Eingabe des Straßennamens.</li>
			<li>Suche nach Löschzug: <br>Alle Hydranten eines Löschzugs werden auf einer Karte dargestellt.
		</ul>
		<p>Bei jeder Karte besteht die Möglichkeit, sich die eigene Position (falls diese ermittelt werden kann) neben den Hydranten-Positionen anzeigen zu lassen.</p>

		<!--  <img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="<?= $config["urls"]["intranet_home"] ?>/images/manual/maps.jpg" style="width: 75%;"> -->
	</div>

	<div class="mt-5" id="checkup">
		<h4>
			Hydrantenprüfung erfassen <a href="#">&uarr;</a>
		</h4>
		
		<p>Anmerkung: Unter dem Menüpunkt "Hydrantenprüfung" -> "Download Formblatt" kann das Formblatt heruntergeladen und ausgedruckt werden.</p> 

		<p>Geprüfte Hydranten können über das Formular (Menüpunkt "Hydrantenprüfung" -> "Neue Prüfung") eingegeben werden. 
		Die erfassten Daten werden dann zur Abrechnung per E-Mail unter anderem an die Feuerwehr-Verwaltung versendet.</p>
		
		<p>Über den Button "Hydrant hinzufügen" können mehrere Hydranten erfasst werden. 
		Mit dem Button "X" kann der jeweilige Hydrant entfernt werden (Achtung: Die Daten werden ohne Rückfrage gelöscht).</p>
		
		<p>Zu erfassende Daten:</p>
		<ul>
			<li>Datum des Tages der Hydrantenprüfung</li>
			<li>Namen, aller an der Hydrantenprüfung beteilitgen Personen</li>
			<li>Pro geprüftem Hydrant:<ul>
					<li>HY-Nummer</li>
					<li>Art des Hydranten</li>
					<li>Alle festgestellten Mängel bzw. dass kein Mangel vorhanden ist</li>
				</ul>
			</li>
			<li>Beschreibungen zu Mängeln oder sonstige Anmerkungen können in das Feld "Hinweise" eingetragen werden.</li>
			<li>Optional kann noch das verwendete Fahrzeug angegeben werden.</li>
		</ul>
		
		<p>Der Zug wird über den angemeldeten Nutzer automatisch erfasst.</p>

		<!-- <img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="images/manual/Report_Create.jpg" style="width: 75%;"> -->

	</div>

	<div class="mt-5" id="checkupassist">
		<h4>
			Hydrantenprüfung mit Assistent erfassen <a href="#">&uarr;</a>
		</h4>

		<p>Anmerkung: Unter dem Menüpunkt "Hydrantenprüfung" -> "Download Formblatt" kann das Formblatt heruntergeladen und ausgedruckt werden.</p> 

		<p>Zur einfacheren Eingabe der Daten, steht ein Assistenz zur Verfügung. In diesem werden die geprüften Hydranten Schritt für Schritt erfasst. 
		Dieser kann unter dem Menüpunkt "Hydrantenprüfung" -> "Neue Prüfung (Assistent)" erreicht werden.</p>
		
		<p>Über den Button "Hydrant hinzufügen" können mehrere Hydranten erfasst werden. 
		Mit dem Button "Hydrant entfernen" kann der aktuell angezeigte Hydrant entfernt werden (Achtung: Die Daten werden ohne Rückfrage gelöscht).</p>
		
		<p>Jeder Hydrant wird in einem eigenen Tab erfasst, ein Wechseln zwischen den Tabs ist jederzeit möglich.</p>
		
		<p>Besonderheiten:</p>
		<ul>
			<li>Neben der HY-Nummer kann auch die früher verwendete FID-Nummer eingegeben werden. 
			Bei der Eingabe wird automatisch die zugehörigen HY-Nummer ermittelt und eingetragen.</li>
			<li>In der Regel sind die Hydranten ohne Mängel, daher ist dies als Standard festgelegt.</li>
			<li>Die Mängelliste wird nur angezeigt, wenn die Optional "Kein Mangel" nicht ausgewählt ist.</li>
			<li>Bei der Auswahl des Typs wird die Mängelliste gefiltert und nur Mängel angezeigt, die möglich sind.</li>
			<li>Allgemeine Daten werden beim Abschluss des Berichts erfasst.</li>
		</ul>
		
		<p>Wichtig:</p>
		<p>Die erfassten Daten werden nicht gespeichert. Bei Abschluss des Assistenten werden alle Daten noch einmal als Übersicht angezeigt. 
		Dort können die Daten auch noch geändert werden. Erst dort werden die Daten mit "Speichern" gespeichert und als E-Mail versendet.</p>
	
	</div>

	<div class="mt-5" id="checkupadmin">
		<h4>
			Prüfungsübersicht- und verwaltung <a href="#">&uarr;</a>
		</h4>
		<p>Unter dem Menüpunkt "Hydrantenprüfung" -> "Prüfungsübersicht" können alle erfassten Hydrantenprüfungen des eigenen Zuges angezeigt werden. Die Feuerwehr-Verwaltung sieht an dieser Stelle alle Prüfberichte.</p>
		
		<p>Prüfungen können:<p>
		<ul>
			<li>angezeigt</li>
			<li>bearbeitet</li>
			<li>gelöscht</li>
			<li>als PDF heruntergeladen werden</li>
		</ul>
		
		<p>werden.</p>
				
		<p>Wird eine Prüfung bearbeitet, wird der aktualisierte Bericht erneut per Mail versendet.</p>

		<!-- <img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="images/manual/Event_Create.jpg" style="width: 75%;"> -->

	</div>

	<div class="mt-5" id="checkupcandidates">
		<h4>
			Aktuelle Prüfobjekte <a href="#">&uarr;</a>
		</h4>
		<p>Unter dem Menüpunkt "Hydrantenprüfung" -> "Aktuelle Prüfobjekte" können alle Hydranten angezeigt werden, deren Prüfung länger als der vorgegebene Prüfzyklus zurückliegt (oder noch nie geprüft wurden).</p>
		
		<p>Die Hydranten werden als Karte und als Liste mit HY-Nummer, Straße/Ortsteil, letzer Prüfung und Prüfzyklus angezeigt. Über den Button "Karte" kann man sich die Detailkarte jedes Hydranten anzeigen lassen.</p>
		
		<p>Eine Suche oder Sortierung der einzelnen Spalten ist, wie in jeder Tabelle, möglich.</p> 
		
		<!--  <img class="img-fluid rounded mb-2 mx-auto d-block border"
			src="images/manual/Event_Overview.jpg" style="width: 75%;"> -->

	</div>
</div>

<div class="mt-5">
	<h4>
		<a href="#">&uarr;</a>
	</h4>
	<p>Stand 13.10.2019</p>
</div>