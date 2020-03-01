<?php
include('db_conn.php');
session_start();

if (isset($_SESSION["logged"]) && $_SESSION["logged"] && isset($_GET["categoria"])) {
    $user = $_SESSION["email"];
    $categoria = $_GET["categoria"];
    $delete = "DELETE FROM PREFERENZA WHERE Utente = '$user' AND Categoria = '$categoria'";
    pg_query($db, $delete);
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>