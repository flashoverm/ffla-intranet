******************************
Release Documentation
******************************

### V2.5.4
ALTER TABLE user_privilege DROP FOREIGN KEY user_privilege_ibfk_1;
ALTER TABLE user_privilege CHANGE privilege privilege VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL; 
DROP TABLE privilege;

UPDATE user_privilege SET privilege = 'FILEADMIN' WHERE user_privilege.privilege = '5873791F-68EC-159D-EF91-3288F02EF1D2'; 
UPDATE user_privilege SET privilege = 'FFADMINISTRATION' WHERE user_privilege.privilege = '10590E6B-FC09-49B3-6A35-53759D10D1FC'; 
UPDATE user_privilege SET privilege = 'MASTERDATAADMIN' WHERE user_privilege.privilege = 'E2CA260A-FFA1-09D3-6C31-F32F231454F9'; 
UPDATE user_privilege SET privilege = 'ENGINEHYDRANTMANANGER' WHERE user_privilege.privilege = '6B296269-6280-EAC5-B5F3-4A95C3FA7656'; 
UPDATE user_privilege SET privilege = 'HYDRANTADMINISTRATOR' WHERE user_privilege.privilege = '2B3DE880-1EB7-C9A1-C533-BD90F773FDBA'; 
UPDATE user_privilege SET privilege = 'PORTALADMIN' WHERE user_privilege.privilege = 'EE50BFB0-B4B0-2AE2-AAE4-2FB6EE9DA558'; 
UPDATE user_privilege SET privilege = 'EDITUSER' WHERE user_privilege.privilege = '231C64FA-24F4-CDA4-60FE-B211A364D5AE'; 
UPDATE user_privilege SET privilege = 'EVENTPARTICIPENT' WHERE user_privilege.privilege = 'C4E19AFC-14CA-9714-B0E6-B1354EC0571C'; 
UPDATE user_privilege SET privilege = 'EVENTMANAGER' WHERE user_privilege.privilege = '26F7145B-826A-F731-4F59-E435B2E94F81'; 
UPDATE user_privilege SET privilege = 'EVENTADMIN' WHERE user_privilege.privilege = '9941EE1E-6E61-0656-E72B-18A4EE48633C'; 

ALTER TABLE confirmation ADD assigned_to CHAR(36) NULL AFTER user; 
ALTER TABLE confirmation ADD CONSTRAINT confirmation_ibfk_3 FOREIGN KEY (assigned_to) REFERENCES user(uuid) ON DELETE RESTRICT ON UPDATE RESTRICT; 

### V2.5.3
CREATE TABLE mailqueue (
						  uuid CHARACTER(36) NOT NULL,
                          timestamp datetime NOT NULL,
						  recipient VARCHAR(255) NOT NULL,
						  subject VARCHAR(255) NOT NULL,
						  body TEXT,
                          PRIMARY KEY (uuid)
                          );

### V2.5.2
CREATE TABLE user_setting (
                          user CHAR(36) NOT NULL,
                          setting VARCHAR(128) NOT NULL,
                          PRIMARY KEY  (user, setting),
						  FOREIGN KEY (user) REFERENCES user(uuid)
                          ) COLLATE  utf8mb4_general_ci;
INSERT INTO engine (uuid, name, isadministration, shortname) VALUES ('0B789F33-B8B4-42A6-3AD7-3FC917441CA0', 'KEZ', '0', 'KEZ') 

### V2.5.1

ALTER TABLE event CHANGE deleted_by canceled_by CHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL; 
ALTER TABLE event ADD cancelationReason VARCHAR(255) AFTER canceled_by; 

### V2.5.0

ALTER TABLE report_staff ADD user CHAR(36) NULL AFTER engine; 
ALTER TABLE report_staff ADD CONSTRAINT report_staff_ibfk_3 FOREIGN KEY (user) REFERENCES user(uuid) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE report_staff CHANGE name name VARCHAR(96) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL; 
ALTER TABLE report_staff CHANGE engine engine CHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL; 

### V2.4.7

UPDATE staffposition SET position = 'Dienstgrad (Gruppenführer)' WHERE staffposition.uuid = '28F8486C-1F14-4293-6BB6-59A959281FE3'; 
UPDATE staffposition SET position = 'Dienstgrad (Zugführer)' WHERE staffposition.uuid = 'BE8BA2F1-11B0-F8DB-292D-8F054A797214'; 
INSERT INTO staffposition (uuid, position, list_index) VALUES ('D7962C08-A1CE-ADB4-5FE2-AAF219E0BDE8', 'Dienstgrad (Verbrandsführer)', '10');
UPDATE staffposition SET list_index = '20' WHERE staffposition.uuid = 'BE8BA2F1-11B0-F8DB-292D-8F054A797214'; 
UPDATE staffposition SET list_index = '30' WHERE staffposition.uuid = '28F8486C-1F14-4293-6BB6-59A959281FE3'; 
UPDATE staffposition SET list_index = '40' WHERE staffposition.uuid = 'C6C83E5B-660D-33A5-3B45-B4B2E4F13F23'; 
UPDATE staffposition SET list_index = '50' WHERE staffposition.uuid = '22BEB994-A05A-0195-4512-ED05FC84AE9C'; 
UPDATE staffposition SET list_index = '60' WHERE staffposition.uuid = 'DAA45E2B-7691-3CF3-4D0D-0C1A39DD0003'; 
UPDATE staffposition SET list_index = '70' WHERE staffposition.uuid = '9CB30C8D-9ABD-487E-3385-3957B0ECD560'; 
UPDATE staffposition SET position = 'Wachmann/-frau' WHERE staffposition.uuid = '9CB30C8D-9ABD-487E-3385-3957B0ECD560'; 

### V2.4.6

ALTER TABLE eventtype CHANGE isseries sendNoReport TINYINT(1) NOT NULL DEFAULT '0'; 

### V2.4.5

ALTER TABLE report ADD createDate DATETIME NULL AFTER creator;
ALTER TABLE report ADD managerApprovedDate DATETIME NULL AFTER managerApproved;  
ALTER TABLE report ADD managerApprovedBy CHAR(36) NULL AFTER managerApprovedDate; 
ALTER TABLE report ADD CONSTRAINT report_ibfk_4 FOREIGN KEY (managerApprovedBy) REFERENCES user(uuid) ON DELETE RESTRICT ON UPDATE RESTRICT; 

### V2.4.4

SELECT report.creator, user.firstname, user.lastname, user.uuid
FROM report, user 
WHERE report.creator LIKE CONCAT('%',user.lastname,'%')
AND report.creator LIKE CONCAT('%',user.firstname,'%')
ORDER BY user.lastname

Run replace_report_creator.php

ALTER TABLE report CHANGE creator creator CHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL; 
ALTER TABLE report ADD CONSTRAINT report_ibfk_3 FOREIGN KEY (creator) REFERENCES user(uuid) ON DELETE RESTRICT ON UPDATE RESTRICT;

Deployment Code 

### V2.4.3

ALTER TABLE user ADD last_login DATETIME NULL AFTER deleted; 

### V2.4.2

UI updates confirmations (more lists)
Last update timestamp

UPDATE report SET type = '325FF3CA-62BE-3F3E-88D8-A1C932BE600B' WHERE type = '00155A58-8720-29CF-42F0-713895C7BFDA';
UPDATE event SET type = '325FF3CA-62BE-3F3E-88D8-A1C932BE600B' WHERE type = '00155A58-8720-29CF-42F0-713895C7BFDA';
DELETE FROM stafftemplate WHERE eventtype = '00155A58-8720-29CF-42F0-713895C7BFDA';
DELETE FROM eventtype WHERE uuid = '00155A58-8720-29CF-42F0-713895C7BFDA';

ALTER TABLE confirmation ADD last_update DATE NULL AFTER last_advisor; 
ALTER TABLE datachangerequest ADD last_update DATE NULL AFTER last_advisor; 

#### V2.4.1

Feature: Checked hydrants list

#### V2.4

Feature: Added multible/additional engines

ALTER TABLE user_privilege ADD engine CHAR(36) NOT NULL AFTER privilege; 
ALTER TABLE user_privilege DROP PRIMARY KEY, ADD PRIMARY KEY(privilege, engine, user);
UPDATE user_privilege SET engine = (SELECT engine FROM user WHERE user_privilege.user = uuid);
ALTER TABLE user_privilege ADD FOREIGN KEY (engine) REFERENCES engine(uuid);

CREATE TABLE additional_engines (
                          user CHARACTER(36) NOT NULL,
                          engine CHARACTER(36) NOT NULL,
                          PRIMARY KEY  (user, engine),
						  FOREIGN KEY (user) REFERENCES user(uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid) );
						  
ALTER TABLE engine ADD shortname VARCHAR(32) NOT NULL AFTER isadministration; 
UPDATE engine SET shortname = "Verw." WHERE uuid LIKE "9BEECEFA-56CF-A009-0059-99DAA5FA0D4E";
UPDATE engine SET shortname = "LZ 1/2" WHERE uuid LIKE "2BAA144B-F946-1524-E60E-7DD485FE1881";
UPDATE engine SET shortname = "LZ 3" WHERE uuid LIKE "9704558C-9A89-A5B0-7CDE-0321A518DCB1";
UPDATE engine SET shortname = "LZ 4" WHERE uuid LIKE "B0C263B5-6416-B8F5-B7A2-4ED57E2123BE";
UPDATE engine SET shortname = "LZ 5" WHERE uuid LIKE "A67C8A08-3BCD-6FA0-9BF4-491A5121EA7B";
UPDATE engine SET shortname = "LZ 6" WHERE uuid LIKE "6D9D8344-BE44-BFD3-1B0F-72BE5E56571E";
UPDATE engine SET shortname = "LZ 7" WHERE uuid LIKE "C440BB6A-D8BF-3FAB-FC57-FAE475A1DBED";
UPDATE engine SET shortname = "LZ 8" WHERE uuid LIKE "1311075E-1260-2685-0822-8102BE480F32";
UPDATE engine SET shortname = "LZ 9" WHERE uuid LIKE "67CF2ADD-F5ED-3D43-FFF1-C504B8F39743";
UPDATE engine SET shortname = "BZ" WHERE uuid LIKE "ACCEC110-290E-6A65-A750-6AA93625D784";
UPDATE engine SET shortname = "-" WHERE uuid LIKE "57D2CB43-F3CE-3837-4181-2FE60FDB9277";
INSERT INTO engine (uuid, name, isadministration, shortname) VALUES ('FEE13FE0-CDE3-AD5F-8A25-851467C12C26', 'UG ÖEL', false, 'UG ÖEL');


#### V2.3.2

Feature:
	Added password-forgot function
	Token functionality 
	Added done and declined datachangerequests to process
	
#### V2.3.1

Feature:
	Backup data and database to pcloud

#### V2.3.0

Feature:
	Masterdata app to request change of master data of users in "MP Feuer"
	Merge duplicate user script
	Added refund form to confirmation mail

Changes/Refactoring: 
	Applied MVC pattern to business objects
	
Some bugfixes

PHP Update to 7.4
	apt update && apt upgrade
	apt -y install lsb-release apt-transport-https ca-certificates 
	wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
	echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list
	apt update && apt upgrade
	apt install php7.4
	
	a2dismod php7.0
	a2enmod php7.4
	systemctl restart apache2
	
SQL
	INSERT INTO privilege (uuid, privilege, is_default) VALUES ('E2CA260A-FFA1-09D3-6C31-F32F231454F9', 'MASTERDATAADMIN', '0');
	ALTER TABLE staff ADD user_acknowledged BOOLEAN NOT NULL AFTER unconfirmed;  

#### V2.2.2

Features: 
	Added user to accepted confirmation requests
	bootstraping
	separate data folder for application data 
	serparated application configuration from instance configuration

#### V2.2.1

mail-address update
info-text to gather access

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
