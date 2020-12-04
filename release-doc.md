******************************
Release Documentation
******************************

#### V2.2

Release-Notes:
	Add config urls: "employerapp_home" => $url_prefix . "/employer",
	Add config apps: "employer" => "employerapp",
	Add config paths: "confirmations" => $_SERVER ["DOCUMENT_ROOT"] . "/../resources/confirmations/",

	ALTER TABLE user ADD employer_address VARCHAR(255) NULL AFTER locked; 
	ALTER TABLE user ADD employer_mail VARCHAR(255) NULL AFTER employer_address; 
	ALTER TABLE user ADD deleted BOOLEAN NOT NULL AFTER locked; 
	
New app:
	employer confirmations
	generic pdf creation and print functionality
	user deletion

##### V2.1.2

Release-Notes:
	config.php: Add setting deactivateOutgoingMails (set to false in prod, true on dev and test)
	Execute resources/library/scripts/sql/migrate_privileges.sql
	Execute: UPDATE privilege SET privilege = 'EDITUSER' WHERE privilege.uuid = '231C64FA-24F4-CDA4-60FE-B211A364D5AE'; 
	
Features:
	User can edit their data by themselves
	Privileges can be added by default to new users (self registration, by admin)
	Application Logbook
	Extra button to subscribe for manager (not assign himself)
	Event particiants can unscribe themselfes

##### V2.1.1

Features:
	Mail logbook for sent mails (and errors)
	Show user by rights
	
Bugfix:
	Mail attachment error
	Send button missing on edit hydrant inspection
	clear form after creating new user

##### V2.1.0

Release-Notes:
	Add Setting useDefaultMapMarker to config
	Update inspection criteria 8
	Update .htaccess

Bugfixes:
	Date sorting

Features:
	Print button/view for events including QR-Codes
	Validation (hiding buttons, dialog) of hydrant counts (none, just one) for inspections 
	Autocomplete district/street in hydrant edit
	Plan inspection feature
	Show password on password fields


##### V2.0.0

Merge of guardian app into intranet

Config-Update
htaccess-Update

Migration:
	Merge development branch in master
	Shutdown guardian webserver: apachectl stop
	Dump guardian database: mysqldump -u root -p guardian > dump_guardian.sql
	Move dump_guardian.sql to import-location
	Shutdown intranet webserver
	Pull master repo
	Create new database "guardian_dump" and import dump
	Change resource/config.php and html.htaccess (from sample)
	Run migration script /resources/library/scripts/migrate_guardian.php
	Start intranet webserver
	
	
##### V1.1.0

Config:
define("HYDRANTADMINISTRATOR", "HYDRANTADMINISTRATOR");

HTACCESS
RewriteRule ^html/hydrant/([^/]+)/edit/?$ 	/ffla-intranet/html/hydrantapp/hydrant_edit.php?hydrant=$1 [L]
RewriteRule ^html/hydrant/new/?$ 		/ffla-intranet/html/hydrantapp/hydrant_edit.php [L]

SQL
ALTER TABLE hydrant ADD operating BOOLEAN NOT NULL AFTER checkbyff;
UPDATE hydrant SET operating=true 
INSERT INTO user (uuid, username, password, engine, rights) VALUES ('89A402EB-C0FD-39DA-C704-699CD18932A8', 'hydrantenadmin', 'set-password', '57D2CB43-F3CE-3837-4181-2FE60FDB9277', '[\"HYDRANTADMINISTRATOR\"]')

##### V1.0.0
