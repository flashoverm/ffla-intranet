******************************
Release Documentation
******************************

##### V2.1.2

Release-Notes:
	Execute resources/library/scripts/sql/migrate_privileges.sql
	
	UPDATE privilege SET privilege = 'EDITUSER' WHERE privilege.uuid = '231C64FA-24F4-CDA4-60FE-B211A364D5AE'; 
	
Features:
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

******************************
New installation
******************************

Clone repository in webserver folder (e.g. /var/www/)

Replace and adapt htaccess and config samples

Create new database and fill with the data
(hydrants, mailings)


apt install -y gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget php7.0-gd

Install nodejs

 	curl -sL https://deb.nodesource.com/setup_12.x | bash -
	apt-get install nodejs
	node -v
	npm -v


Change nodejs path in config file
Run in folder {application}/resources/library/puppeteer/
	npm init 
	(set name to fflaintranet)
	npm i --save puppeteer

Create folder "reports" and check access/write rights
