<?php
include('db_conn.php');
session_start();

if (isset($_GET["fiera"]) && isset($_GET["num"])) {
  $fiera = $_GET["fiera"];
  $num = $_GET["num"];
}

$query = "SELECT * FROM edizione WHERE fiera='$fiera' AND numero='$num'";
//echo $query;
$result = pg_query($db, $query);
$righeTot = pg_num_rows($result);
if ($righeTot == 1) {
  $row = pg_fetch_row($result);
  $dataInizio = $row[2];
  $dataFine = $row[3];
  $indirizzo = $row[4];
  $prezzoEntrata = $row[5];
  $prezzoRidotto = $row[6];
  $provincia = $row[7];



  $giornoCorrente = date("Y-m-d");
  $title = $fiera;
  $quando = "Dal $dataInizio al $dataFine";
  $dove = "$indirizzo, $provincia";
  $prezzi = "Intero: $prezzoEntrata, Ridotto: $prezzoRidotto";

  $query = "SELECT * FROM esposizione as e join azienda as a on e.azienda = a.partitaiva WHERE e.fiera='$fiera' AND e.numedizione='$num'";
  //echo $query;
  $aziende = pg_query($db, $query);
  $righeTot = pg_num_rows($aziende);

  $query = "SELECT * FROM recensione as r join utente as u on r.utente = u.email WHERE fiera='$fiera' AND numedizione='$num'";
  //echo $query;
  $recensioni = pg_query($db, $query);
  $righeTot = pg_num_rows($recensioni);
} else {
  header('Location: index.php');
}


?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Fiere Italia<?php echo " | " . $fiera . " " . $num . "^ edizione"; ?></title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
  <!-- Google Fonts Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" href="css/style.css">
  <link href="star-rating-plugin/css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
  <link href="star-rating-plugin/themes/krajee-fas/theme.css" media="all" rel="stylesheet" type="text/css" />

</head>

<body>

  <header>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-inverse navbar-dark indigo">

      <!-- Navbar brand -->
      <a class="navbar-brand" href="index.php">Fiere Italia</a>

      <!-- Collapse button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav" aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Collapsible content -->
      <div class="collapse navbar-collapse" id="basicExampleNav">

        <!-- Links -->
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contatti</a>
          </li>

        </ul>
        <!-- Links -->

        <?php
        if (isset($_SESSION["logged"]) && $_SESSION["logged"])
          echo '<div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                    <ul class="navbar-nav ml-auto">
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown"
                          aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-user"></i> ' . $_SESSION["nome"] . " " . $_SESSION["cognome"] . ' </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-info" aria-labelledby="navbarDropdownMenuLink-4">
                          <a class="dropdown-item" href="logout.php"> <i class="fas fa-sign-out-alt"></i> Log out</a>
                        </div>
                      </li>
                    </ul>
                  </div>';
        else
          echo '<form class="form-inline">
                    <button class="btn btn-sm align-middle btn-outline-white" type="button" data-toggle="modal"
                      data-target="#modalLoginForm">Accedi</button>
                </form>';
        ?>

      </div>
      <!-- Collapsible content -->

    </nav>
    <!--/.Navbar-->
  </header>

  <!-- Login Modal -->
  <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="login.php" method="post">
          <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold">Accedi</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body mx-3">
            <div class="md-form mb-5">
              <i class="fas fa-envelope prefix grey-text"></i>
              <input type="email" name="email" id="defaultForm-email" class="form-control validate">
              <label data-error="errore" for="defaultForm-email">Email</label>
            </div>

            <div class="md-form mb-4">
              <i class="fas fa-lock prefix grey-text"></i>
              <input type="password" name="password" id="defaultForm-pass" class="form-control validate">
              <label data-error="errore" for="defaultForm-pass">Password</label>
            </div>

          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-primary" type="submit">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--/. Login Modal -->

  <main>
    <!--Main layout-->
    <div class="container">

      <!--Page heading-->
      <div class="row">
        <div class="col-md-8">
          <h1 class="h1-responsive"><?php echo $title; ?>
            <small class="text-muted"><?php echo ($num) . "^ edizione"; ?> </small>
          </h1>
        </div>
        <div class="col-md-4 text-right">
          <a href="lista_edizioni.php?fiera=<?php echo $fiera; ?>" type="button" class="btn btn-light"><i class="fas fa-caret-left fa-lg"></i> Torna alla lista</a>
        </div>
      </div>
      <!--/.Page heading-->

      <div aria-live="polite" aria-atomic="true">
        <div class="toast animated slideInRight" style="position: absolute; top: 70px; right: 50px;" data-autohide="true" data-delay="4000">
          <div class="toast-header">

            <strong class="mr-auto">Notifica</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="toast-body">
            <?php
            if (isset($_SESSION["logged"])) {
              if ($_SESSION["logged"])  echo 'Accesso avvenuto correttamente';
              else echo 'Attenzione! Email e/o password errati';
            }
            ?>
          </div>
        </div>
      </div>


      <hr>
      <div class="row">
        <div class="col-md-6">
          <br>
          <h3>Informazioni</h3><br>
          <table class="table">
            <tbody>
              <tr>
                <td><i class="fas fa-map-marked-alt fa-lg"></i> Dove</td>
                <td><?php echo $dove; ?></td>
              </tr>
              <tr>
                <td><i class="fas fa-calendar-alt fa-lg"></i> Quando</td>
                <td><?php echo $quando; ?></td>
              </tr>
              <tr>
                <td><i class="fas fa-ticket-alt fa-lg"></i> Prezzi</td>
                <td><?php echo $prezzi; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Azienda</th>
                <th scope="col">Descrizione</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $echoAziende = "";
              $count = 1;
              while ($row = pg_fetch_row($aziende)) {
                $echoAziende += "<tr><td>$count</td><td>$row[5]</td><td>$row[1]</td></tr>";
                $count++;
              }
              echo $echoAziende;
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php // media delle recensioni dell'edizione.
          $query = "SELECT voto FROM recensione where fiera = '$fiera' AND numedizione='$num'";
          $result = pg_query($db, $query);
          if (pg_num_rows($result) > 0) {
            $tot = pg_num_rows($result);
            $media = 0;
            while ($row = pg_fetch_row($result)) {
              $media += intval($row[0]);
            }
            $media = $media / $tot;
            echo '
                  <hr><h5>Votazione media:</h5>
                  <input id="input-3-ltr-star-xs" class="kv-ltr-theme-fas-star-3 rating-loading" value="' . $media . '"
                   dir="ltr" data-size="xs" data-show-clear="false">
                ';
          }
          ?>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-12">
          <?php //Inserimento valutazioni/commenti
          if (isset($_SESSION["logged"]) && $_SESSION["logged"]) {
            //controllo se e' gia' iniziata l'edizione della fiera
            if ($dataInizio <= $giornoCorrente) {
              $utente = $_SESSION["email"];
              $query = "SELECT * FROM recensione where utente = '$utente' AND fiera = '$fiera' AND numedizione='$num'";
              $result = pg_query($db, $query);
              if (pg_num_rows($result) < 1) {
                echo '
                <h4>Lascia una recensione</h4><br>
                 <form action="inserisciRecensione.php" method="GET">
                 <input id="input-2-ltr-star-sm" name="stars" class="kv-ltr-theme-fas-star rating-loading" data-step="1"
                  data-show-caption="false" dir="ltr" data-size="sm" data-show-clear="false" required="required">
                 <input type="hidden" name="fiera" value="' . $fiera . '">
                 <input type="hidden" name="num" value="' . $num . '">
                   <div class="md-form input-group mb-3">
                     <input type="text" class="form-control" name="text" placeholder="Testo"
                       aria-describedby="MaterialButton-addon2" required="required">
                     <div class="input-group-append">
                       <button class="btn btn-md btn-outline-mdb-color m-0 px-3 py-2 z-depth-0 waves-effect" type="submit">Pubblica</button>
                     </div>
                   </div>
                 </form>

                ';
              } else {

                $row = pg_fetch_row($result);
                $stelle = "";
                for ($i = 0; $i < $row[4]; $i++) {
                  $stelle = $stelle . '&#9733;';
                }
                echo '
                <blockquote class="blockquote text-left">
                  <h6 class="text-right" style="margin-bottom:-5px; margin-top:-5px;"><a href="elimina_commento.php?fiera=' . $fiera . '&num=' . $num . '">[ELIMINA]</a></h6>
                  <p class="mb-0">' . $row[5] . '</p>
                  <footer class="blockquote-footer mb-3"><cite title="Source Title">' . $_SESSION["nome"] . ' ' . $_SESSION["cognome"] . ' ' . $stelle . '</cite></footer>
                </blockquote>
                ';
              }
            } else {
              echo '<h4>Non è ancora possibile pubblicare recensioni</h4>';
            }
          } else {
            echo '
            <h4>Accedi o registrati per lasciare una recensione</h4><br>
            ';
          }

          //lista di tutte le recensioni

          $echoRecensioni = "";
          $count = 1;
          if (isset($_SESSION["logged"]) && $_SESSION["logged"]) {
            $email = $_SESSION["email"];
          }
          while ($row = pg_fetch_row($recensioni)) {
            if (!isset($email)) {
              $stelle = "";
              for ($i = 0; $i < $row[4]; $i++) {
                $stelle = $stelle . '&#9733;';
              }
              echo '
                <blockquote class="blockquote text-left">
                  <p class="mb-0">' . $row[5] . '</p>
                  <footer class="blockquote-footer mb-3"><cite title="Source Title">' . $row[9] . ' ' . $row[10] . ' ' . $stelle . '</cite></footer>
                </blockquote>
                ';
            } else {
              if ($row[0] != $email) {
                $stelle = "";
                for ($i = 0; $i < $row[4]; $i++) {
                  $stelle = $stelle . '&#9733;';
                }
                echo '
                  <blockquote class="blockquote text-left">
                    <p class="mb-0">' . $row[5] . '</p>
                    <footer class="blockquote-footer mb-3"><cite title="Source Title">' . $row[9] . ' ' . $row[10] . ' ' . $stelle . '</cite></footer>
                  </blockquote>
                  ';
              }
            }
            $count++;
          }

          ?>
        </div>
      </div>
    </div>
    <!--/.Main layout-->
  </main>


  <!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- combostar -->
  <!--	<script src="src/jquery.combostars.js"></script> -->
  <!--	star-rating-plugin -->
  <script src="star-rating-plugin/js/star-rating.js" type="text/javascript"></script>
  <script src="star-rating-plugin/themes/krajee-fas/theme.js" type="text/javascript"></script>
  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('.kv-ltr-theme-fas-star').rating({
        hoverOnClear: false,
        theme: 'krajee-fas',
        containerClass: 'is-star'
      });
    });
    $(document).ready(function() {
      $('.kv-ltr-theme-fas-star-3').rating({
        hoverOnClear: false,
        step: 0.1,
        starCaptions: function(val) {
          if (val == 1) return val + ' stella';
          return val + ' stelle';
        },
        starCaptionClasses: function(val) {
          if (val == 0) {
            return 'badge badge-default';
          } else if (val < 3) {
            return 'badge badge-danger';
          } else {
            return 'badge badge-success';
          }
        },
        theme: 'krajee-fas',
        containerClass: 'is-star',
        displayOnly: true
      });
    });


    <?php
    if (isset($_SESSION["notify"]) && $_SESSION["notify"]) {
      echo "$('.toast').toast('show');";
      $_SESSION["notify"] = false;
    }
    ?>
  </script>

</body>


</html>