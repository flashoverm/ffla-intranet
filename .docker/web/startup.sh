#!/usr/bin/env sh

# wait for database container
while ! mysqladmin ping -h ${MYSQL_HOST} --silent; do
	echo "wait for database..."
    sleep 1
done


configfile=/var/www/resources/config/instanceConfig.php
configtemplate=/var/www/resources/config/instanceConfig.sample.php

if [ ! -e $configfile ]; then
	echo "Creating basic config"
	cp $configtemplate $configfile
fi

apache2-foreground