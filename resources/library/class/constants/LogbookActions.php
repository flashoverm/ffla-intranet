<?php
require_once realpath(dirname(__FILE__) . "/../../../../resources/config.php");

$logbookActions = array(

	/*
	 * 	User
	 */
	1 => "Benutzer angelegt",
	2 => "Benutzer aktualisiert",
	3 => "Benutzer gesperrt",
	4 => "Benutzer entsperrt",
	5 => "Benutzer entfernt",
	6 => "Benutzerpasswort hinzugefügt",
	7 => "Benutzerpasswort zurückgesetzt",
		
	21 => "Benutzer hat sich angemeldet",
	22 => "Benutzer hat falsches Passwort eingegeben",	

	/*
	 * Mail
	 */
	31 => "E-Mail wurde versandt",
	32 => "E-Mail konnte nicht gesendet werden",
	33 => "E-Mail wurde nur geloggt (Debug-Mode)",
		
	/*
	 * Guradian - Event
	 */
	101 => "Wache angelegt",
	102 => "Wache aktualisiert",
	103 => "Wache entfernt",
	104 => "Wache aus Datenbank entfernt",
	105 => "Wache veröffentlicht",

	/*
	 * Guradian - Event Staff
	 */
	111 => "Einer Wache zugewiesen",
	112 => "Für Wache eingetragen",
	113 => "Für Wache eingetragen (Bestätigung ausstehend)",
	114 => "Wachteilnahme bestätigt",
	115 => "Wachteilnehmer entfernt",

	/*
	 * Guradian - Staff Template
	 */
	131 => "Wachpersonal-Vorlage erstellt",
	132 => "Wachpersonal-Vorlage aktualisiert",
	133 => "Wachpersonal-Vorlage entfernt",

	/*
	 * Guradian - Report
	 */
	150 => "Wachbericht erstellt",
	151 => "Wachbericht aus Wache erstellt",
	152 => "Wachbericht aktualisiert",
	153 => "Wachbericht entfernt",
	154 => "Wachebericht aus Datenbank entfernt",

	156 => "Wachbreicht freigegeben",
	157 => "Wachbericht-Freigabe entfernt",
	158 => "Wachbericht-EMS-Eintrag angelegt",
	159 => "Wachbericht-EMS-Eintrag entfernt",

		
	190 => "Wachberichte exportiert",
		
	/*
	 * Hydrant
	 */
	201 => "Hydrant angelegt",
	202 => "Hydrant aktualisiert",
	203 => "Hydrant entfernt",

	/*
	 * Hydrant Inspection
	 */
	211 => "Hydrantenprüfung angelegt",
	212 => "Hydrantenprüfung aktualisiert",
	213 => "Hydrantenprüfung entfernt",
	214 => "Hydrantenprüfung aus Datenbank entfernt",

		
	/*
	 * 	Files
	 */
	301 => "Formular/Datei angelegt",
	302 => "Formular/Datei aktualsiert",
	303 => "Formular/Datei entfernt",

);

abstract class LogbookActions {

	const UserCreated = 1;
	const UserUpdated = 2;
	const UserLocked = 3;
	const UserUnlocked = 4;
	const UserDeleted = 5;		//Not in use
	const UserAddedPassword = 6;
	const UserResetPassword = 7;
	
	const UserLogedIn = 21;
	const UserLoginFailed = 22;
	
		
	/*
	 * Mail
	 */
	const MailSent = 31;
	const MailFailed = 32;
	const MailDebug = 33;
	
	
	/*
	 * Guradian - Event
	 */
	const EventCreated = 101;
	const EventUpdated = 102;
	const EventDeleted = 103;
	const EventDeletedDB = 104;
	const EventPublished = 105;
	
	/*
	 * Guradian - Event Staff
	 */
	const EventAssigned = 111;
	const EventSubscribed = 112;
	const EventSubscribedPending = 113;		//Not in use
	const EventStaffConfirmed = 114;
	const EventUnscribed = 115;
	
	
	/*
	 * Guradian - Staff Template
	 */
	const StaffTemplateCreated = 131;		//Not in use
	const StaffTemplateUpdated = 132;		//Not in use
	const StaffTemplateDeleted = 133;		//Not in use
	
	
	/*
	 * Guradian - Report
	 */
	const ReportCreated = 150;
	const ReportCreatedFromEvent = 151;		//Not in use
	const ReportUpdated = 152;
	const ReportDeleted = 153;
	const ReportDeletedDB = 154;			//Not in use
		
	const ReportApproved = 156;
	const ReportApprovRemoved = 157;
	const ReportEMSSet = 158;
	const ReportEMSUnset = 159;
	
	const ReportsExported = 190;
	
	
	/*
	 * Hydrant
	 */
	const HydrantCreated = 201;
	const HydrantUpdated = 202;
	const HydrantDeleted = 203;			//Not in use
	
	/*
	 * Hydrant Inspection
	 */
	const InspectionCreated = 211;
	const InspectionUpdated = 212;
	const InspectionDeleted = 213;
	const InspectionDeletedDB = 214;	//Not in use

	
	/*
	 * 	Files
	 */
	const FileCreated = 301;
	const FileUpdated = 302;			//Not in use
	const FileDeleted = 303;
	
}