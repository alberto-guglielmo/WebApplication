<?php
	session_start();
	$_SESSION["email"]=$_POST["email"];
	$_SESSION["password"]=$_POST["password"];
	include("db_conn.php");

	$result = pg_query($db, "SELECT * FROM UTENTE WHERE Email = '".$_POST["email"]."' AND Password ='".$_POST["password"]."'");

	if(pg_num_rows($result) > 0) {
		$row = pg_fetch_row($result);
		$_SESSION["logged"] = true;
		$_SESSION["nome"] = $row[3];
		$_SESSION["cognome"] = $row[4];
	} else {
		$_SESSION["logged"] = false;
	}
	$_SESSION["notify"] = true;
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
