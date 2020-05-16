<?php
ob_start();
// Include config file
require_once "sql_connect.php";
require_once "functions.php";
//include 'functions.php';
// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = $pin = "";
$username_err = $password_err = $confirm_password_err = $email_err = $pin_err = "";
 
// Processing form data when form is submitted
if(!empty($_POST)){
    // Validate username
    if(empty(trim($_POST["nick"]))){
        $username_err = "Proszę wpisać nick!";
    } else{
        $username_path = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
        // filtrowanie wulgaryzmów 
        include('forbidden.php');
        if(in_array(trim($_POST['nick']),$forbidden)){
            header("Location: register.php?fail=Nick zawiera wulgaryzmy!");
            exit;
        }
        if(strlen(trim($_POST['nick'])<3)){
            header("Location: register.php?fail=Nick jest za krótki!");
            exit;
        }
        if(preg_match($username_path,trim($_POST['nick']))){
            header("Location: register.php?fail=Nick zawiera znaki specjalne!");
            exit;
        }
        // Prepare a select statement
        $sql = "SELECT id FROM users_ WHERE nick = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["nick"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "Ten nick jest już zajęty!";
                    header("Location: register.php?fail=Ten nick jest już zajęty!");
                    exit;
                } else{
                    $username = trim($_POST["nick"]);
                }
            } else{
                    header("Location: register.php?fail=Wystąpił błąd, spróbuj ponownie");
                    exit;
            }
        }

        // Close statement
        $stmt->close();
    }

    //Walidacja email
    if(empty(trim($_POST["email"]))) {
      $email_err = "E-mail jest pusty!";
    } else {
      $check_email = "/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,4}$/";
      $param_email = trim($_POST["email"]);
      if(!preg_match($check_email, $param_email)){
        $email_err = "Adres e-mail jest niepoprawny!";
        header("Location: register.php?fail=Adres e-mail jest niepoprawny");
        exit;
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users_ WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $email_err = "Ten email jest już zajęty!";
                    header("Location: register.php?fail=Adres e-mail jest już zajęty");
                    exit;
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                header("Location: register.php?fail=Wystąpił błąd, spróbuj ponownie");
                exit;
            }
        }

        // Close statement
        $stmt->close();
    }
}
    
   

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
        header("Location register.php/?fail=Pin musi składać się z 4 cyfr");
        exit;
    } else{
        $pin = trim($_POST['pin']);
    }

    // Captcha


    /*
    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = 'YOUR_RECAPTCHA_SECRET_KEY';
    $recaptcha_response = $_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Take action based on the score returned:
    if ($recaptcha->score < 0.5) {
        header("Location: register.php?fail=Błąd weryfikacji antyspamowej");
    }*/


    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
        
        // Prepare an insert statement
        $kod = md5($email);
        $sql = "INSERT INTO users_ (nick, password, email, pin, code) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameteprs
      
            $stmt->bind_param("sssss", $param_username, $param_password,$param_email, $param_pin,$kod);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;
            $param_pin = $pin;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
               
        
               send_mail($kod,$email);
               echo'<script type="text/javascript">
                window.location.href = "login.php?done=Na podany adres został wysłany link aktywacyjny";
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