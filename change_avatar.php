<?php
session_start();
require_once('functions.php');
$target_dir = "avatars/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "Plik nie jest obrazem";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo '<script>location.href="index.php?fail=Taki plik już istnieje";</script>';
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo '<script>location.href="index.php?fail=Plik jest za duży";</script>';
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo '<script>location.href="index.php?fail=Niedozwolony format pliku";</script>';

    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo '<script>location.href="index.php?fail=Plik nie został wysłany";</script>';

// if everything is ok, try to upload file
} else {
  $name = $target_dir.'avatar_'.$_SESSION['id'].'.'.$imageFileType;
  delete_old($target_dir,$_SESSION['id']);

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $name)) {
    resize_image($target_dir.'avatar_'.$_SESSION['id'].'.'.$imageFileType,$_SESSION['id'],'');

      change_avatar($target_dir.'avatar_'.$_SESSION['id'].'.'.$imageFileType,$_SESSION['id']);
    } else {
        echo '<script>location.href="index.php?fail=Wysyłanie nie powiodło się";</script>';
    }
}
?>