<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");

abstract class LogbookActions {
	
	/*
	 * 	User
	 */
	const UserCreated = array(001, "Benutzer angelegt");
	const UserUpdated = array(002, "Benutzer aktualisiert");
	const UserLocked = array(003, "Benutzer gesperrt");
	const UserUnlocked = array(004, "Benutzer entsperrt");
	const UserDeleted = array(005, "Benutzer entfernt");
	const UserAddedPassword = array(006, "Benutzerpasswort hinzugefügt");
	
	const UserLogedIn = array(021, "Benutzer hat sich angemeldet");
	
	/*
	 * Mail
	 */
	const MailSent = array(031, "E-Mail wurde versandt");
	
	
	/*
	 * Guradian - Event
	 */
	const EventCreated = array(101, "Wache angelegt");
	const EventUpdated = array(102, "Wache aktualisiert");
	const EventDeleted = array(103, "Wache entfernt");
	const EventDeletedDB = array(104, "Wache aus Datenbank entfernt");
	const EventPublished = array(105, "Wache veröffentlicht");
	
	/*
	 * Guradian - Event Staff
	 */
	const EventAssigned = array(111, "Einer Wache zugewiesen");
	const EventSubscribed = array(112, "Für Wache eingetragen");
	const EventSubscribedPending = array(113, "Für Wache eingetragen (Bestätigung ausstehend)");
	const EventStaffConfirmed = array(114, "Wachteilnahme bestätigt");
	const EventUnscribed = array(115, "Wachteilnehmer entfernt");
	
	/*
	 * Guradian - Staff Template
	 */
	const StaffTemplateCreated = array(131, "Wachpersonal-Vorlage erstellt");
	const StaffTemplateUpdated = array(132, "Wachpersonal-Vorlage aktualisiert");
	const StaffTemplateDeleted = array(133, "Wachpersonal-Vorlage entfernt");
	
	/*
	 * Guradian - Report
	 */
	const ReportCreated = array(150, "Wachbericht erstellt");
	const ReportCreatedFromEvent = array(151, "Wachbericht aus Wache erstellt");
	const ReportUpdated = array(152, "Wachbericht aktualisiert");
	const ReportDeleted = array(153, "Wachbericht entfernt");
	const ReportDeletedDB = array(154, "Wachebricht aus Datenbank entfernt");
	const ReportsExported = array(155, "Wachberichte exportiert");
	
	const ReportApproved = array(156, "Wachbreicht freigegeben");
	const ReportApprovRemoved = array(157, "Wachbericht-Freigabe entfernt");
	const ReportEMSSet = array(158, "Wachbericht-EMS-Eintrag angelegt");
	const ReportEMSUnset = array(159, "Wachbericht-EMS-Eintrag entfernt");
	
		
	/*
	 * Hydrant
	 */
	const HydrantCreated = array(201, "Hydrant angelegt");
	const HydrantUpdated = array(202, "Hydrant aktualisiert");
	const HydrantDeleted = array(203, "Hydrant entfernt");
	
	/*
	 * Hydrant Inspection
	 */
	const InspectionCreated = array(211, "Hydrantenprüfung angelegt");
	const InspectionUpdated = array(212, "Hydrantenprüfung aktualisiert");
	const InspectionDeleted = array(213, "Hydrantenprüfung entfernt");
	const InspectionDeletedDB = array(214, "Hydrantenprüfung aus Datenbank entfernt");

	
	/*
	 * 	Files
	 */
	const FileCreated = array(301, "Formular/Datei angelegt");
	const FileUpdated = array(302, "Formular/Datei aktualsiert");
	const FileDeleted = array(303, "Formular/Datei entfernt");
	
}
	