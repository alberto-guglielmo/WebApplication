
<?php
include('db_conn.php');
session_start();

if(isset($_SESSION["logged"]) && isset($_GET["text"]) && isset($_GET["stars"]) && isset($_GET["fiera"]) && isset($_GET["num"])){
  $dataora=date("Y-m-d H:i:s");
  $user = $_SESSION["email"];
  $fiera = $_GET["fiera"];
  $num = $_GET["num"];
  $stars = $_GET["stars"];
  $text = $_GET["text"];
  $insert="INSERT INTO recensione(utente, fiera, numedizione, dataora, voto, testo)VALUES ('$user', '$fiera', '$num', '$dataora', '$stars', '$text')";
  echo $insert;
  pg_query($db, $insert);
}

header('location: edizione.php?fiera='.$fiera.'&num='.$num);

?>
