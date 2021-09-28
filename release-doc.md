******************************
Release Documentation
******************************

#### V2.5

Feature: Message board

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
