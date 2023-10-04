#!/bin/bash

echo "Dump created successfully"
rm -rf /dumps/dump.sql

#echo "CREATE DATABASE $MYSQL_DATABASE;" > /dumps/temp.sql
#echo "USE $MYSQL_DATABASE;" >> /dumps/temp.sql

mysqldump -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE >> /dumps/dump.sql

#mv /dumps/temp.sql /dumps/dump.sql

echo "Dump finished docker/mysql/dumps/dump.sql"
