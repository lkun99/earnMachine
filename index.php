<?php
session_start();
require_once('functions.php');

     $user = login();

$user_stats = user_stats($_SESSION['id']);

if($user['activation'] == 1 ){
    $code =  generate_code();
    $_SESSION['code'] = sha1($code);
}
?>
<!doctype html>
<html lang="pl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Aplikacja</title>
</head>

<body>
    <nav class="navbar navbar-light bg-light">
        <a class="nav-link text-dark ml-auto " href="logout.php">Wyloguj</a>
    </nav>
    <?php

      $output = catch_messages();
      if($output !== null){
      
      echo'  <div class="container">
          <div class="row mt-4">
                '.$output.'
          </div>
      </div>';
      }
      ?>
    <div class="container">

        <h1 class="text-center font-weight-bold mt-5">Wprowadź Kod</h1>

        <?php
                      if($user['activation']==0){
                        echo'<div class="row d-flex justify-content-center mt-5">
                        <div class="alert alert-info col-lg-4 col-md-8 col-sm-12 text-center" role="alert">
                          Aktywuj swoje konto żeby korzystać z serwisu
                        </div>
                        </div>';
                        exit;
                      }
                ?>

        <div class="row d-flex justify-content-center mt-5">
            <div class="alert alert-info col-lg-4 col-md-8 col-sm-12 text-center" role="alert">
                <?php echo $code ?>
            </div>
        </div>


        <form action="verify.php" class=" mt-5" method="POST">
            <div class="col-12 d-flex justify-content-center mt-4">
                <label for="code" class="sr-only">Kod</label>
                <input type="text" name="code" id="code" placeholder="Kod" minlength="15" maxlength="15"
                    onkeyup="validate_code()" required class="col-lg-4 col-md-8 col-sm-12 m-3 form-control">
            </div>
            <div class="col-12 d-flex justify-content-center mt-4">
                <input type="submit" value="OK" class="btn btn-secondary col-lg-4 col-md-8 m-3 col-sm-12">
            </div>
        </form>
    </div>
    <div class="box">
        <div class="box__content">
            <div class="box__title">PROFIL</div>
            <div class="box__description">
                <div class="container ">
                    <div class="card-deck mt-2 d-flex justify-content-center">
                        <div class="card">
                            <div id="avatar-holder">
                                <img src=".<?php echo $user['avatar']; ?>" id="avatar"
                                    class="card-img-top h-25 w-50 mx-auto d-block" alt="avatar">
                                <button onclick="set_file()" id="av-main">ZMIEŃ</button>
                            </div>
                            <div class="card-body">

                                <h5 class="card-title text-center font-weight-bold"><?php echo $user['nick']?></h5>
                                <!--table-->
                                <h6 class="text-center p-4">Wpisanych kodów</h6>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Dziś</th>
                                            <td><?php echo $user_stats[0]; ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Tydzień</th>
                                            <td><?php echo $user_stats[1]; ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Miesiąc</th>
                                            <td><?php echo $user_stats[2]; ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Rok</th>
                                            <td><?php echo $user_stats[3]; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--table-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
    <form id="hidden_form" action="change_avatar.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit" id="submitInput">
    </form>
    <div class="alert text-center cookiealert" role="alert" id="cookie_alert">
        <b>Korzystamy z plików cookies</b> Chcemy zapewnić Ci najwyższy możliwy komfort przeglądania naszej strony
        <a href="https://cookiesandyou.com/" target="_blank">Dowiedz się więcej</a>

        <button type="button" class="btn btn-primary btn-sm acceptcookies" onclick="hide_cookie()" aria-label="Close">
            W porządku
        </button>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/gh/Wruczek/Bootstrap-Cookie-Alert@gh-pages/cookiealert.js"></script>
    <script src="js/myScript.js"></script>
    <script>
    function change_avatar() {
        var avatar = document.getElementById('avatar-holder');
        var avatar_img = document.getElementById('avatar');
        var button = document.getElementById('av-main');
        avatar.addEventListener("mouseenter", function() {
            var i = 100;
            setInterval(function() {
                i--;
                if (i > 30) avatar_img.style.filter = "brightness(" + i + "%)";
                else return 0;
            }, 1);
            button.style.display = "inherit";

        });
        avatar.addEventListener("mouseleave", function() {
            avatar_img.style.filter = "brightness(100%)";
            button.style.display = "none";
        });
    }
    change_avatar();
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