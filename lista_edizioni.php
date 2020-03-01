<?php session_start();
$fiera = $_GET['fiera']; ?>
<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Fiere Italia<?php echo " | " . $fiera; ?></title>
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
        <div class="col-md-12">
          <h1 class="h1-responsive"><?php echo 'Lista edizioni della fiera ' . $fiera; ?>
          </h1>
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
      <?php
      include('db_conn.php');


      //if(isset($_SESSION['username'])) header('location: home.php');


      $query = "SELECT * FROM edizione WHERE fiera='$fiera' ORDER BY dataInizio DESC";
      //echo $query;
      $result = pg_query($db, $query);
      $righeTot = pg_num_rows($result);
      if (isset($_GET['pagina'])) {
        $pagina = $_GET["pagina"];
      } else {
        $pagina = "1";
      }
      $riga = 0;
      $colonna = 0;
      //eliminazione righe inutili
      $righeInutili = ($pagina - 1) * 6;
      for ($i = 0; $i < $righeInutili; $i++) {
        $row = pg_fetch_row($result);
      }
      //eliminazione righe inutili
      echo '<div class="row mt-5 wow">';
      $giornoCorrente = date("Y-m-d");
      while ($row = pg_fetch_row($result)) {
        if ($colonna < 3) {
          //echo "$row[0], $row[1], $row[2], $row[3], $row[4], $row[5]";
          $colonna++;
        } else {
          $riga++;
          if ($riga < 2) {
            $colonna = 1;
            echo '</div><div class="row mt-3">';
          } else {
            break;
          }
        }
        if ($row[3] < $giornoCorrente) {
          $terminata = "[TERMINATA]";
        } else {
          $terminata = "";
        }
        echo '<div class="col-lg-4 animated fadeIn medium"><div class="card"><div class="card-body">';
        echo '<h4 class="card-title">' . $row[0] . '<small> ' . $row[1] . '^ edizione</small></h4><p class="card-text">Dal ' . $row[2] . " al " . $row[3] . "<br>Indirizzo: " . $row[4] . ", " . $row[7] . '<br><br>' . $terminata . '</p>
            <a href="edizione.php?fiera=' . $fiera . '&num=' . $row[1] . '" class="btn btn-primary">Informazioni</a>';
        echo '</div></div></div>';
      }
      echo '</div>';
      ?>
      <hr>

      <!--Pagination-->
      <nav class="row flex-center" <?php if ($righeTot == 0) echo 'style="display:none;"'; ?>>
        <ul class="pagination">
          <li class="page-item<?php if ('1' == $pagina) echo ' disabled'; ?>">
            <a class="page-link" href="<?php echo '?fiera=' . $fiera . '&pagina=' . ($pagina - 1); ?>" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
              <span class="sr-only">Previous</span>
            </a>
          </li>
          <?php
          $pagine = ceil($righeTot / 6);
          $pagineTot = $pagine;
          $count = 1;
          while ($pagine > 0) {
            if ($pagina != $count) {
              echo '<li class="page-item"><a class="page-link" href="?fiera=' . $fiera . '&pagina=' . $count . '">' . $count . '</a></li>';
            } else {
              echo '<li class="page-item active">
                            <a class="page-link" href="">' . $count . ' <span class="sr-only">(current)</span></a>
                          </li>';
            }
            $count++;
            $pagine--;
          }

          ?>

          <li class="page-item<?php if ($pagineTot == $pagina) {
                                echo ' disabled';
                              } ?>">
            <a class="page-link" href="<?php echo '?fiera=' . $fiera . '&pagina=' . ($pagina + 1); ?>" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
              <span class="sr-only">Next</span>
            </a>
          </li>
        </ul>
      </nav>
      <!--/.Pagination-->
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
  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
    <?php
    if (isset($_SESSION["notify"]) && $_SESSION["notify"]) {
      echo "$('.toast').toast('show');";
      $_SESSION["notify"] = false;
    }
    ?>
  </script>

</body>

</html>