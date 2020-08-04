<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));

$util = array (
		
		"head" => "Lieber Nutzer, \n\n",
		"footer" => "\n\n\nIntranet der Freiwilligen Feuerwehr der Stadt Landshut\n\n" . $config ["urls"] ["base_url"] . $config ["urls"] ["intranet_home"],
		"attachment_error" => "\n\n\n - Der Anhang ist aufgrund eines technischen Problems nicht verfügbar - "
);

$bodies = array (
    
    "report_insert" => $util["head"] . "es wurde ein neuer Hydranten-Prüfbericht angelegt. Diesen finden Sie im Anhang oder im Portal unter \n\n",
    
    "report_update" => $util["head"] . "ein Hydranten-Prüfbericht wurde aktualisiert. Diesen finden Sie im Anhang oder im Portal unter \n\n",
	
	"event_insert" => $util["head"] . "in ihrem Zug wurde eine neue Wache eingestellt: \n\n",
	
	"event_update" => $util["head"] . "eine Wache wurde aktualisiert: \n\n",
	
	"event_assign" => $util["head"] . "ihrem Zug wurde eine neue Wache zugewiesen: \n\n",
	
	"event_publish" => $util["head"] . "eine neue Wache wurde veröffentlicht: \n\n",
	
	"event_staff_confirmed" => $util["head"] . "sie können an einer Wache teilnehmen, bei der sie sich eingetragen haben: \n\n",
	
	"event_delete" => $util["head"] . "eine Wache, bei der Sie sich eingetragen haben, wurde abgesagt: \n\n",
	
	"event_subscribe" => $util["head"] . "sie haben sich in eine Wache eingeschrieben: \n\n",
	
	"event_staff_add" => $util["head"] . "sie wurden in eine Wache eingeteilt: \n\n",
	
	"event_report_link" => "\n\n Unter folgendem Link kann im Anschluss der Wache der bereits vorausgefüllte Wachbericht erstellt werden:\n\n",
	
	"event_subscribe_manager" => $util["head"] . "jemand aus Ihrem Zug nimmt an einer Wache teil: \n\n",
	
	"event_full" => $util["head"] . "eine Wache ist voll belegt: \n\n",
	
	"event_subscribe_engine" => $util["head"] . "jemand hat sich in eine Ihrem Zug zugewiesene Wache eingeschrieben: \n\n",
	
	"event_subscribe_engine_confirm" => $util["head"] . "jemand hat sich in eine Ihrem Zug zugewiesene Wache eingeschrieben und muss bestätigt werden: \n\n",
	
	"event_not_full" => $util["head"] . "eine Wache findet in ".$config ["settings"] ["reminderAtDay"]." Tagen statt und hat noch nicht die nötige Besetzung erreicht: \n\n",
	
	"event_unscribe" => $util["head"] . "sie wurden durch den Wachbeauftragten von der Wache entfernt: \n\n",
	
	"event_unscribe_engine" => $util["head"] . "jemand aus Ihrem Zug wurde durch den Wachbeauftragten von der Wache entfernt:  \n\n",
	
	"event_report" => $util["head"] . "ein Wachbericht wurde angelegt und ist als Anhang verfügbar oder unter:  \n\n",
	
	"event_report_update" => $util["head"] . "ein Wachbericht wurde aktualisiert und ist als Anhang verfügbar oder unter: \n\n",
	
	"event_report_approved" => $util["head"] . "ein Wachbericht wurde durch einen Wachbeauftragten überprüft und freigegeben. \n\n Der Bericht befindet sich im Anhang oder unter:  \n\n",
	
	"user_add" => $util["head"] . "für Sie wurde ein Zugang angelegt:",
	"login" => "\n\nLogin: ",
	"password" => "\n\nPasswort: ",
	"user_add2" => "\n\nSie können sich jetzt im Portal unter " . $config ["urls"] ["base_url"] . " anmelden.",
	
	
	"reset_password" => $util["head"] . "ihr Passwort wurde zurückgesetzt:",
	"reset_password2" => "\n\n Sie können es im Portal unter " . $config ["urls"] ["base_url"] . " in ihr Wunschkennwort ändern.",
	
);