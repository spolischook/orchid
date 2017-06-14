#!/bin/bash

php bin/console doctrine:database:drop --force|true
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
