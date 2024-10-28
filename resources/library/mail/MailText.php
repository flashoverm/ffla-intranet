<?php

$util = array (
		
		"head" => "Lieber Nutzer, \n\n",
		"footer" => "\n\n\nIntranet der Freiwilligen Feuerwehr der Stadt Landshut\n\n" . $config ["urls"] ["base_url"] . $config ["urls"] ["intranet_home"],
		"attachment_error" => "\n\n\n - Der Anhang ist aufgrund eines technischen Problems nicht verfügbar - "
);

$bodies = array (
    
    "report_insert" => $util["head"] . "es wurde ein neuer Hydranten-Prüfbericht angelegt. Dieser befindet sich im Anhang oder im Portal unter \n\n",
    
    "report_update" => $util["head"] . "ein Hydranten-Prüfbericht wurde aktualisiert. Dieser befindet sich im Anhang oder im Portal unter \n\n",
    
    "report_not_approved" => $util["head"] . "der folgende Wachbericht muss noch überprüft und freigegeben werden: \n\n",
	
	"event_insert" => $util["head"] . "in ihrem Zug wurde eine neue Wache eingestellt: \n\n",
    
    "event_insert_all" => $util["head"] . "eine neue Wache wurde eingestellt: \n\n",
    "event_insert_all_disc" => "\n\nWillst du diese Mail nicht mehr erhalten? Du kannst sie im Portal unter Einstellungen abbestellen.\n\n",
	
	"event_update" => $util["head"] . "eine Wache wurde aktualisiert: \n\n",
	
	"event_assign" => $util["head"] . "ihrem Zug wurde eine neue Wache zugewiesen: \n\n",
	
	"event_publish" => $util["head"] . "eine neue Wache wurde veröffentlicht: \n\n",
	
	"event_staff_confirmed" => $util["head"] . "Du kannst an einer Wache teilnehmen, an der du dich eingetragen hast: \n\n",
	
	"event_cancel" => $util["head"] . "eine Wache, bei der du dich eingetragen hast, wurde abgesagt: \n\n",
    
    "event_cancel_administration" => $util["head"] . "eine Wache wurde abgesagt: \n\n",
	
	"event_subscribe" => $util["head"] . "Du hast dich in eine Wache eingeschrieben: \n\n",
	
	"event_staff_add" => $util["head"] . "Du wurdest in eine Wache eingeteilt: \n\n",
		
	"event_staff_ack" => "\n\n Bitte bestätige die Kenntnisnahme durch Klick auf folgendem Link:\n\n",
	
	"event_report_link" => "\n\n Unter diesem Link kann im Anschluss der Wache der bereits vorausgefüllte Wachbericht erstellt werden:\n\n",
	
	"event_subscribe_manager" => $util["head"] . "jemand aus deinem Zug nimmt an einer Wache teil: \n\n",
	
	"event_full" => $util["head"] . "eine Wache ist voll belegt: \n\n",
	
	"event_subscribe_engine" => $util["head"] . "jemand hat sich in eine deinem Zug zugewiesene Wache eingeschrieben: \n\n",
	
	"event_subscribe_engine_confirm" => $util["head"] . "jemand hat sich in eine deinem Zug zugewiesene Wache eingeschrieben und muss bestätigt werden: \n\n",
	
	"event_not_full" => $util["head"] . "eine Wache findet in ".$config ["settings"] ["reminderAtDay"]." Tagen statt und hat noch nicht die nötige Besetzung erreicht: \n\n",
	
	"event_unscribe" => $util["head"] . "Du wurdest durch den Wachbeauftragten von der Wache entfernt: \n\n",
		
	"event_unscribe_by_user" => $util["head"] . "jemand hat sich aus eine deinem Zug zugewiesene Wache ausgetragen: \n\n",
			
	"event_unscribe_engine" => $util["head"] . "jemand aus deinem Zug wurde durch den Wachbeauftragten von der Wache entfernt:  \n\n",
	
	"event_unscribe_by_user_engine" => $util["head"] . "jemand aus deinem Zug hat sich aus einer Wache ausgetragen: \n\n",
					
	"confirmation_declined" => $util["head"] . "eine Anfrage einer Arbeitgeberbestätigung wurde abgelehnt.\n\nDer Grund der Ablehnung und die Möglichkeit, den Antrag zu bearbeiten befinden sich unter: \n\n",
		
	"confirmation_accepted" => $util["head"] . "eine Anfrage einer Arbeitgeberbestätigung wurde akzeptiert. \n\nDie Bestätigung befindet sich im Anhang oder unter: \n\n",
    
    "confirmation_accepted_info" => $util["head"] . "eine Anfrage einer Arbeitgeberbestätigung wurde durch einen Einheitsführer akzeptiert. \n\nDie Bestätigung befindet sich im Anhang oder unter: \n\n",
		
	"datachange_requested" => $util["head"] . "eine neue Anfrage einer Stammdatenänderung wurde erstellt.\n\nBearbeitung der Anfragen unter: \n\n",

	"datachange_declined" => $util["head"] . "eine Anfrage einer Stammdatenänderung wurde abgelehnt.\n\nÜbersicht aller Anfragen unter:\n\n",
	
	"datachange_done" => $util["head"] . "eine Anfrage einer Stammdatenänderung wurde umgesetzt.\n\nÜbersicht aller Anfragen unter:\n\n",
		
	"datachange_request" => $util["head"] . "zu einer Anfrage einer Stammdatenänderung gibt es eine Rückfrage.\n\nÜbersicht aller Anfragen unter:\n\n",
		
	"datachange_request_update" => $util["head"] . "eine Stammdatenänderung mit offener Rückfrage wurde aktualisiert.\n\nBearbeitung der Anfragen unter: \n\n",
		
   
);