docker exec database sh -c 'exec mysqldump --all-databases -uroot -p"$MYSQL_ROOT_PASSWORD"'
