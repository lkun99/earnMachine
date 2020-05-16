<?php
ob_start();
require_once('sql_connect.php');
if(isset($_GET['code'])) {  
    $code = trim($_GET['code']);
        $sql_2 = "UPDATE users_ SET activation = 1 WHERE code = ?";
        if($stmt = $mysqli->prepare($sql_2)) {
            if($stmt->bind_param('s',$code))
                $stmt->execute();
                header("Location:login.php?done=Konto zostało aktywowane!");
        } else {
            die('błąd zapytania '.$mysqli->errno);
        }
    } 
?>