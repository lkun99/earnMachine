<?php

 
// Include config file
require_once "sql_connect.php";
require_once "functions.php";

 
// Processing form data when form is submitted
if(!empty($_POST)){

    // Check if email is empty
    if(empty(trim($_POST["nick"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["nick"]);
    }
    
  
    if(empty($email_err) ){
        // Prepare an update statement
        $sql = "UPDATE users_ SET password_reset = ? WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $code = sha1(time()).sha1($email).md5(time());
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $code,$email);
            
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                                     
                            send_mail_reset($code,$email);
                            // Redirect user to welcome page
                            echo'<script type="text/javascript">
                            window.location.href = "login.php?done=Na podany email wysłano kod resetowania hasła";
                            </script>';
                            exit;
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                            echo'<script type="text/javascript">
                            window.location.href = "login.php?fail=Resetowanie hasła nie powiodło się!";
                            </script>';
                            exit;
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                    echo'<script type="text/javascript">
                    window.location.href = "login.php?fail=Taki email/email nie istnieje!";
                    </script>';
                    exit;
                }
                $mysqli->close();
            } 
        
       



?>