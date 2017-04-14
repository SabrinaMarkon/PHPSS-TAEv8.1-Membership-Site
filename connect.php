<?php
$MySqlHostname = "localhost"; //the name of your host - if its local leave it as is.
$MySqlUsername = "DATABASE_USERNAME"; //the username to your database.
$MySqlPassword = "DATABASE_PASSWORD"; //the password to your database.
$MySqlDatabase = "DATABASE_NAME"; //the name of your database.
$dblink=MYSQL_CONNECT($MySqlHostname, $MySqlUsername, $MySqlPassword) or die("Could not connect to database");
@mysql_select_db("$MySqlDatabase") or die( "Could not select database");
?>