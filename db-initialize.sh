# Wait to be sure that SQL Server came up

echo "Waiting for SQL Server to come up..."


sleep 70s

# Run the setup script to create the DB and the schema in the DB
# Note: make sure that your password matches what is in the Dockerfile
/opt/mssql-tools/bin/sqlcmd -S db -U sa -P "SecRet1~2#" -d master -i create-database.sql  &
echo "Created laravel database "


sleep 8s

echo "Now migrating .... "

# why not migrate the database?
php artisan migrate &

echo "successfully migrated database !"
