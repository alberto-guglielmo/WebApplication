<?php
	$host        = "host = localhost";
	$port        = "port = 5432";
	$dbname      = "dbname = fiere";
	$credentials = "user = postgres password=admin";

	$db = pg_connect( "$host $port $dbname $credentials"  );
	if(!$db)
		echo "Error : Unable to open database\n";
?>
