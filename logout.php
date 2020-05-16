<?php
session_start();
require 'functions.php';
clean_login($_SESSION['id']);
session_destroy();
echo "<script>location.href='login.php?done=Poprawnie wylogowano'</script>";
exit;