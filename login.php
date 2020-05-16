<?php
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1){
    header("Location: index.php");
}
require_once('functions.php');
$output = catch_messages();
?>

<!doctype html>
<html lang="pl">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no,user-scalable=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Logowanie</title>
  </head>
  <body>
  <?php
    if($output !== null){
        echo'  <div class="container">
            <div class="row mt-4">
                  '.$output.'
            </div>
        </div>';
        }
        ?>

        <div class="col-12 mt-5">
                <h1 class="text-center font-weight-bold">Witamy w aplikacji</h1>
        </div>
        
        <form action="login_user.php" class="form-signin mt-5" method="POST">
            <div class="col-12 d-flex justify-content-center mt-4">
                <label for="name" class="sr-only">Nazwa Użytkownika/Email</label>
                <input type="text" name="nick" id="nick" pattern="[^-,]+" required placeholder="Nazwa użytkownika/email" autocomplete="email"  class="col-lg-4 col-md-8 col-sm-12 m-3 form-control">
            </div>
            <div class="col-12 d-flex justify-content-center mt-2">
                <label for="password" class="sr-only">Hasło</label>
                <input type="password" name="password" id="password" minlength="6" required placeholder="Hasło" autocomplete="password"  class="col-lg-4 col-md-8 col-sm-12 m-3 form-control">
            </div>
            <div class="col-12 d-flex justify-content-center mt-2">
                <label for="pin" class="sr-only">PIN</label>
                <input type="password" name="pin" id="code" required placeholder="PIN" minlength="4" maxlength="4"  onkeydown="validate_code()" class="col-lg-4 col-md-8 col-sm-12 m-3 form-control">
            </div>
            <div class="col-12 d-flex justify-content-center mt-4">
                <input type="submit" value="Zaloguj" class="btn btn-secondary col-lg-4 col-md-8 m-3 col-sm-12">
            </div>
            <div class="col-12 d-flex justify-content-center mt-1">
            <a class="btn btn-success col-lg-4 col-md-8 m-3 col-sm-12" href="register.php">Zarejestruj się</a>
            </div>
        </form>

    <!-- Optional JavaScript -->
    <script src="js/myScript.js"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>