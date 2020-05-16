<?php
ob_start();
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("Location: index.php");
    exit;
}
 
// Include config file
require_once "sql_connect.php";
require_once "functions.php";
// Define variables and initialize with empty values
$nick = $password = $pin = "";
$nick_err = $password_err = $pin_err = "";
 
// Processing form data when form is submitted
if(!empty($_POST)){

    // Check if nick is empty
    if(empty(trim($_POST["nick"]))){
        $nick_err = "Please enter nick.";
    } else{
        $nick = trim($_POST["nick"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    //Check if PIN is empty
    if(empty(trim($_POST["pin"]))){
        $pin_err = "Please enter your pin.";
    } else{
        $pin = trim($_POST["pin"]);
    }
    // Validate credentials
    if(empty($nick_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, nick, password,pin,logged,ip FROM users_ WHERE nick = ? OR email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_nick,$param_nick);
            
            // Set parameters
            $param_nick = $nick;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if nick exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($id, $nick, $hashed_password,$db_pin,$last_logged,$ip);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password) && $pin = $db_pin){
                            // Password is correct, so start a new session
                            check_login($last_logged,$ip);
                            session_destroy();
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["nick"] = $nick;    
                                                    
                            update_login($_SERVER['REMOTE_ADDR'],$id);
                            // Redirect user to welcome page
                            echo'<script type="text/javascript">
                            window.location.href = "index.php";
                            </script>';
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                            echo'<script type="text/javascript">
                            window.location.href = "login.php?fail=Błędne hasło!";
                            </script>';
                        }
                    }
                } else{
                    // Display an error message if nick doesn't exist
                    $nick_err = "No account found with that nick.";
                    echo'<script type="text/javascript">
                    window.location.href = "login.php?fail=Taki nick/email nie istnieje!";
                    </script>';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
}
?>