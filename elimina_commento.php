
<?php
include('db_conn.php');
session_start();
if(isset($_SESSION["logged"]) && isset($_GET["fiera"]) && isset($_GET["num"])){
  $user = $_SESSION["email"];
  $fiera = $_GET["fiera"];
  $num = $_GET["num"];
  $delete="DELETE FROM recensione WHERE utente='$user' AND fiera='$fiera' AND numedizione='$num'";
  pg_query($db, $delete);
  header('location: edizione.php?fiera='.$fiera.'&num='.$num);
}else{
  header('location: index.php');
}

?>
