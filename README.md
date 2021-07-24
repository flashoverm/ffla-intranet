# intranet

Trello Board for collaboration with feature backlog and bug-tracking:

https://trello.com/b/JHfXwwFF/intranet-feuerwehr-landshut

## Stages

Production:
 
	https://intranet.feuerwehr-landshut.de/

Test: 

	https://intranet.feuerwehr-landshut.de/test/

## Branches

	master 		->	goes in production (via jenkins)

	test    	->	goes on test (via jenkins)

	feature-xyz	->	feature xyz is developed

	bugfix-xyz	->	bugfix xyz is developed

## Rules

Smaller (single commit) features and bugfixes can be fixed directly on the test branch

Bigger features and bugfixes are developed in their on branch

### Release/Merging

feature- and bugfix-branches are merged into test

test is merged into master with a new version number


## Usage

New installation:

Clone repository in webserver folder (e.g. /var/www/)

Create new database and fill with the data (hydrants)

Replace and adapt config sample

Install composer: https://getcomposer.org/download/

Run composer install (or php composer.phar install) in /resources/ folder

apt install zip

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

check access/write rights  "reports, inspections, files, ..."
