FROM mariadb:10.4

COPY .docker/database/config/slow.cnf /etc/mysql/conf.d/

ADD .docker/database/fflaintranet.sql /docker-entrypoint-initdb.d/ddl.sql