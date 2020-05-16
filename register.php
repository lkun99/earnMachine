<?php
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1){
    header("Location: index.php");
}
require_once('functions.php');
$output = catch_messages();
if($output !== null){

echo'  <div class="container">
    <div class="row mt-4">
          '.$output.'
    </div>
</div>';
}

?>
<!doctype html>
<html lang="pl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no,user-scalable=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Logowanie</title>
</head>

<body>
    <div class="col-12 mt-5">
        <h1 class="text-center font-weight-bold">Witamy w aplikacji</h1>
    </div>

    <form action="register_user.php" class="form-signin mt-5" method="POST" onsubmit=" return validate_form()">
        <div class="col-12 d-flex justify-content-center mt-4">
            <label for="nick" class="sr-only">Nazwa Użytkownika</label>
            <input type="text" name="nick" id="nick" required pattern="[^-,]+" placeholder="Nazwa użytkownika"
                class="col-lg-4 col-md-8 col-sm-12 m-3 form-control">
        </div>
        <div class="col-12 d-flex justify-content-center mt-2">
            <label for="name" class="sr-only">Adres e-mail</label>
            <input type="email" name="email" id="email" required placeholder="Adres e-mail"
                class="col-lg-4 col-md-8 col-sm-12 m-3 form-control">
        </div>

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
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

        <div class="col-12 d-flex justify-content-center mt-4">
            <input class="btn btn-success col-lg-4 col-md-8 m-3 col-sm-12" type="submit" value="Zarejestruj się">
        </div>
        <div class="col-12 d-flex justify-content-center mt-1">
            <a href="login.php" class="btn btn-secondary col-lg-4 col-md-8 m-3 col-sm-12">Mam już konto</a>
        </div>

    </form>
    <div class="alert text-center cookiealert" id="cookie_alert" role="alert">
        <b>Korzystamy z plików cookies</b> Chcemy zapewnić Ci najwyższy możliwy komfort przeglądania naszej strony
        <a href="https://cookiesandyou.com/" target="_blank">Dowiedz się więcej</a>

        <button type="button" class="btn btn-primary btn-sm acceptcookies" onclick="hide_cookie()" aria-label="Close">
            W porządku
        </button>
    </div>
    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/gh/Wruczek/Bootstrap-Cookie-Alert@gh-pages/cookiealert.js"></script>

    <script src="js/myScript.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=YOUR_RECAPTCHA_SITE_KEY"></script>
    <script>
    grecaptcha.ready(function() {
        grecaptcha.execute('YOUR_RECAPTCHA_SITE_KEY', {
            action: 'contact'
        }).then(function(token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
        });
    });
    </script>
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
</html>