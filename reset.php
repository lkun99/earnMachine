<?php
session_start();
if(!empty($_GET)){
    
    require 'functions.php';
    $code = trim($_GET['code']);
    $_SESSION['code'] = $code;
    if(!check_code($code)){
        header("Location: login.php?fail=Błąd resetowania");
        exit;
    } 
}
?>
<!doctype html>
<html lang="pl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Reset Hasła</title>
</head>

<body>
    <div class="col-12 mt-5">
        <h1 class="text-center font-weight-bold">Wprowadź nowe hasło</h1>
    </div>

    <form action="reset_password.php" class="form-signin mt-5" method="POST" onsubmit=" return validate_form()">


        <div class="col-12 d-flex justify-content-center mt-2">
            <label for="password" class="sr-only">Hasło</label>
            <input type="password" name="password" id="password" required placeholder="Hasło"
                class="col-lg-4 col-md-8 col-sm-12 m-3 form-control">
        </div>
        <div class="col-12 d-flex justify-content-center mt-2">
            <label for="password" class="sr-only">Powtórz Hasło</label>
            <input type="password" name="confirm_password" id="confirm_password" minlength="6" required
                placeholder="Powtórz Hasło" class="col-lg-4 col-md-8 col-sm-12 m-3 form-control">
        </div>
        <div class="col-12 d-flex justify-content-center mt-2">
            <label for="pin" class="sr-only">PIN</label>
            <input type="password" name="pin" id="code" placeholder="PIN" maxlength="4" onkeyup="validate_code()"
                required class="col-lg-4 col-md-8 col-sm-12 m-3 form-control">
        </div>
        

        <div class="col-12 d-flex justify-content-center mt-4">
            <input class="btn btn-success col-lg-4 col-md-8 m-3 col-sm-12" type="submit" value="Zmień hasło">
        </div>


    </form>

    <!-- Optional JavaScript -->

    <script src="js/myScript.js"></script>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>