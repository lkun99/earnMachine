<?php
session_start();
if(isset($_POST['code'])){
        require_once('functions.php');
    if(check_cookie()){
        $user = check_cookie();
    }
    else {
        $user = login();
    }   
    $code = sha1(trim($_POST['code']));

    if($_SESSION['code'] == $code) {
        insert_request(trim($_POST['code']),$_SESSION['id']);
    } else {
        header("Location: index.php?fail=Niepoprawnie wpisany kod");
    }
}