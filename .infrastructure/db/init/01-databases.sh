#!/usr/bin/env bash

set -e

mysql -uroot -p${MYSQL_ROOT_PASSWORD} -e "DROP DATABASE IF EXISTS test; CREATE DATABASE test DEFAULT CHARACTER SET utf8mb4;"
mysql -uroot -p${MYSQL_ROOT_PASSWORD} -e "GRANT ALL ON test.* TO '${MYSQL_USER}'@'%' IDENTIFIED BY '${MYSQL_PASSWORD}'";
