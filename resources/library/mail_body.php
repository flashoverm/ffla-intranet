<?php

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
	
	"event_staff_confirmed" => $util["head"] . "Sie können an einer Wache teilnehmen, bei der Sie sich eingetragen haben: \n\n",
	
	"event_delete" => $util["head"] . "eine Wache, bei der Sie sich eingetragen haben, wurde abgesagt: \n\n",
	
	"event_subscribe" => $util["head"] . "Sie haben sich in eine Wache eingeschrieben: \n\n",
	
	"event_staff_add" => $util["head"] . "Sie wurden in eine Wache eingeteilt: \n\n",
		
	"event_staff_ack" => "\n\n Bitte bestätigen Sie die Kenntnisnahme durch Klick auf folgendem Link:\n\n",
	
	"event_report_link" => "\n\n Unter diesem Link kann im Anschluss der Wache der bereits vorausgefüllte Wachbericht erstellt werden:\n\n",
	
	"event_subscribe_manager" => $util["head"] . "jemand aus Ihrem Zug nimmt an einer Wache teil: \n\n",
	
	"event_full" => $util["head"] . "eine Wache ist voll belegt: \n\n",
	
	"event_subscribe_engine" => $util["head"] . "jemand hat sich in eine Ihrem Zug zugewiesene Wache eingeschrieben: \n\n",
	
	"event_subscribe_engine_confirm" => $util["head"] . "jemand hat sich in eine Ihrem Zug zugewiesene Wache eingeschrieben und muss bestätigt werden: \n\n",
	
	"event_not_full" => $util["head"] . "eine Wache findet in ".$config ["settings"] ["reminderAtDay"]." Tagen statt und hat noch nicht die nötige Besetzung erreicht: \n\n",
	
	"event_unscribe" => $util["head"] . "Sie wurden durch den Wachbeauftragten von der Wache entfernt: \n\n",
		
	"event_unscribe_by_user" => $util["head"] . "jemand hat sich aus eine Ihrem Zug zugewiesene Wache ausgetragen: \n\n",
			
	"event_unscribe_engine" => $util["head"] . "jemand aus Ihrem Zug wurde durch den Wachbeauftragten von der Wache entfernt:  \n\n",
	
	"event_unscribe_by_user_engine" => $util["head"] . "jemand aus Ihrem Zug hat sich aus einer Wache ausgetragen: \n\n",
			
	"confirmation_requested" => $util["head"] . "eine neue Anfrage einer Arbeitgeberbestätigung wurde erstellt.\n\nBearbeitung der Anfragen unter: \n\n", 
		
	"confirmation_declined" => $util["head"] . "eine Anfrage einer Arbeitgeberbestätigung wurde abgelehnt.\n\nDer Grund der Ablehnung und die Möglichkeit, den Antrag zu bearbeiten sind zu finden unter: \n\n",
		
	"confirmation_accepted" => $util["head"] . "eine Anfrage einer Arbeitgeberbestätigung wurde akzeptiert. \n\nDie Bestätigung befindet sich im Anhang oder unter: \n\n",
		
	"datachange_requested" => $util["head"] . "eine neue Anfrage einer Stammdatenänderung wurde erstellt.\n\nBearbeitung der Anfragen unter: \n\n",

	"datachange_declined" => $util["head"] . "eine Anfrage einer Stammdatenänderung wurde abgelehnt.\n\nÜbersicht aller Anfragen unter:\n\n",
	
	"datachange_done" => $util["head"] . "eine Anfrage einer Stammdatenänderung wurde umgesetzt.\n\nÜbersicht aller Anfragen unter:\n\n",
		
	"datachange_request" => $util["head"] . "zu einer Anfrage einer Stammdatenänderung gibt es eine Rückfrage.\n\nÜbersicht aller Anfragen unter:\n\n",
		
	"datachange_request_update" => $util["head"] . "eine Stammdatenänderung mit offener Rückfrage wurde aktualisiert.\n\nBearbeitung der Anfragen unter: \n\n",
		
   
);