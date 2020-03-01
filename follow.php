<?php
include('db_conn.php');
session_start();

if (isset($_SESSION["logged"]) && $_SESSION["logged"] && isset($_GET["categoria"])) {
    $user = $_SESSION["email"];
    $categoria = $_GET["categoria"];
    $insert = "INSERT INTO PREFERENZA(utente, categoria) VALUES ('$user', '$categoria')";
    pg_query($db, $insert);
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>