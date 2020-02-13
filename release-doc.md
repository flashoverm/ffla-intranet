******************************
Release Documentation
******************************

RewriteRule ^html/hydrant/([^/]+)/edit/?$ 	/ffla-intranet/html/hydrantapp/hydrant_edit.php?hydrant=$1 [L]
RewriteRule ^html/hydrant/new/?$ 		/ffla-intranet/html/hydrantapp/hydrant_edit.php [L]

ALTER TABLE hydrant ADD operating BOOLEAN NOT NULL AFTER checkbyff;
UPDATE hydrant SET operating=true 

******************************
New installation
******************************

Clone repository in webserver folder (e.g. /var/www/)

Replace and adapt htaccess and config samples

Create new database and fill with the data
(hydrants, mailings)


apt install -y gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget

Install nodejs
Change nodejs path in config file
Run in folder .../resources/library/puppeteer/
	npm init #set name to fflaintranet
	npm i --save puppeteer
