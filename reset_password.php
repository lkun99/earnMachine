<?php
ob_start();
session_start();
// Include config file
require_once "sql_connect.php";
require_once "functions.php";
//include 'functions.php';
// Define variables and initialize with empty values
$password = $pin ="";
$password_err = $pin_err=  "";
 
// Processing form data when form is submitted
if(!empty($_POST)){
    // Validate username
   
   

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Proszę wpisać hasło!";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Hasło za krótkie"; 
        header("Location: register.php?fail=Hasło musi się składać co najmniej z 6 znaków");
        exit;
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Proszę potwierdzić hasło";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(($password != $confirm_password)){
            header("Location: register.php?fail=Hasła nie pasują do siebie");
            exit;
        }
    }
    // Validate PIN !!!!
   
    $reg = '/^[0-9]{4}$/';
    if(!preg_match($reg,trim($_POST['pin']))){
        header("Location register.php?fail=Pin musi składać się z 4 cyfr");
        exit;
    } else{
        $pin = trim($_POST['pin']);
    }

   
    // Check input errors before inserting in database
    if( empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
      
        $sql = "UPDATE users_ SET password = ?, pin = ? WHERE password_reset = ?";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameteprs
      
            $stmt->bind_param("sss", $param_password,$param_pin,$_SESSION['code']);
            
            // Set parameters
      
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_pin = $pin;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
               
        
            
               echo'<script type="text/javascript">
                window.location.href = "login.php?done=Zresetowano hasło";
                </script>';
            } else{
                echo "Wystąpił błąd, spróbuj ponownie";
            }
        }
         
     
    }
    else{
        die('Wprowadzone wartości są niepoprawne!');
    }
    
    
    // Close connection
    $mysqli->close();
} 
?>