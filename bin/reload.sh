#!/bin/bash

ENVIRORMENT=${2:-prod};
[[ $ENVIRORMENT = prod ]] && CONFIG_SUFFIX="" || CONFIG_SUFFIX="_"$ENVIRORMENT;
rm -rf app/cache/${ENVIRORMENT}

DB=$(sed -n -e 's/^.*database_name: //p' app/config/parameters${CONFIG_SUFFIX}.yml);
DB_HOST=$(sed -n -e 's/^.*database_host: //p' app/config/parameters${CONFIG_SUFFIX}.yml);
DB_USER=$(sed -n -e 's/^.*database_user: //p' app/config/parameters${CONFIG_SUFFIX}.yml);
DB_PASS=$(sed -n -e 's/^.*database_password: //p' app/config/parameters${CONFIG_SUFFIX}.yml);

MYSQL_PWD="$DB_PASS";
mysql -e "drop database $DB;" -u $DB_USER || true;
mysql -e "create database $DB;" -u $DB_USER;

php bin/console doctrine:schema:create
php bin/console hautelook:fixtures:load -n
