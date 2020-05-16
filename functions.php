<?php
ob_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
function login() {
   // Initialize the session
    
   // Check if the user is logged in, if not then redirect him to login page
   if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != 1){
       header("location: login.php");
       exit;
   }
   require 'sql_connect.php'; 
   $sql = "SELECT * FROM users_ WHERE id = ?";
   $stat = $mysqli->prepare($sql);
   $stat->bind_param('i',$_SESSION['id']);
   $stat->execute();
   $result = $stat->get_result();
   return $all = $result->fetch_assoc();

}
function check_cookie() {
    if(isset($_COOKIE['stuff'])){
        $token = $_COOKIE['stuff'];
        require 'sql_connect.php';
        $sql = "SELECT * FROM users_ WHERE token = ?";
        if($stmt = $mysqli->prepare($sql)){
            if($stmt->bind_param('s',$token)){
                $stmt->execute();
                $result = $stmt->get_result();
                $all = $result->fetch_assoc();
                $_SESSION['id'] = $all['id'];
                $_SESSION['loggedin'] = 1;
                return $all;
            }
        }    
    } else {
        return false;
    }
}
function insert_request($code,$user_id){
    include('sql_connect.php');
    $sql = "INSERT INTO requests (user_id,code,date) VALUES(?,?,NOW())";
    echo $user_id.' '.$code;
    if($statement = $mysqli->prepare($sql)){
        if($statement->bind_param('is',$user_id,$code)){
            if($statement->execute()){
                $sql_2 = "SELECT COUNT(id) FROM requests WHERE user_id = ? AND (DATE <= NOW());";
                if($stat = $mysqli->prepare($sql_2)){
                    if($stat->bind_param('i',$user_id)){
                        $stat->execute();
                        $res = $stat->get_result();
                        $ans = $res->fetch_row();
                        die($ans[0]);
                        update_points($user_id,$ans[0]);
                        header("Location:index.php?done=Poprawnie Wpisano Kod ");
                    }
                }
            }
        } else {
            die("Unable to bind params when inserting a new request!");
        }
    } else {
        die("Incorrect querry when inserting a new request!");
    }
}
function catch_messages(){
    if(!empty($_GET)){
    if(isset($_GET['done'])){
        $message = 
        '<div class="alert alert-success col-12 text-center" role="alert">
        '.$_GET['done'].'
        </div>';
    }
    if(isset($_GET['fail'])){
        $message = 
        '<div class="alert alert-danger col-12 text-center" role="alert">
        '.$_GET['fail'].'
        </div>';
    }
    return $message;
} else {
    return null;
}
    
}
function user_stats($user_id){
    require 'sql_connect.php';
    $day = "SELECT COUNT(id) FROM requests WHERE user_id = ? AND (DATE BETWEEN date_sub(NOW(),INTERVAL 1 DAY) AND NOW());";
    $week = "SELECT COUNT(id) FROM requests WHERE user_id = ? AND (DATE BETWEEN date_sub(NOW(),INTERVAL 1 WEEK) AND NOW());";
    $month = "SELECT COUNT(id) FROM requests WHERE user_id = ? AND (DATE BETWEEN date_sub(NOW(),INTERVAL 1 MONTH) AND NOW());";
    $year = "SELECT COUNT(id) FROM requests WHERE user_id = ? AND (DATE BETWEEN date_sub(NOW(),INTERVAL 1 YEAR) AND NOW());";
    $queries =[];
    $all = [];
    array_push($queries,$day,$week,$month,$year,$whole_time);
    foreach($queries as $q){
    if($stat = $mysqli->prepare($q)){
        if($stat->bind_param('i',$user_id)){
            $stat->execute();
            $res = $stat->get_result();
            $ans = $res->fetch_row();
           array_push($all,$ans[0]); 
        }
    }
}
    update_points($user_id,$ans[4]);
    return $all;
}
function update_points($user_id,$points){
    require 'sql_connect.php';
    $sql = "UPDATE users_ SET points = ? WHERE id = ?";
    if($stmt->$mysqli->prepare($sql)){
        if($stmt->bind_param('ii',$points,$user_id)){
            $stmt->execute();
        }
    }
}
function generate_code(){

$code_length = 15;
$code = '';
while(true) {
	$rand = mt_rand(0,9);
    $code.=$rand;
	   if(strlen($code)==$code_length) break;
}
    return $code;
}
function send_mail($kod, $email) {


    /* If you installed PHPMailer without Composer do this instead: */
    
    require './mail/src/Exception.php';
    require './mail/src/PHPMailer.php';
    require './mail/src/SMTP.php';



    
    
    /* Create a new PHPMailer object. Passing TRUE to the constructor enables exceptions. */
    $mail = new PHPMailer(TRUE);
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    /* Open the try/catch block. */
    try {
       /* Set the mail sender. */
       $mail->setFrom('admin@lkun2699.nazwa.pl', 'Football Hero');
    
       /* Add a recipient. */
       $mail->addAddress($email, 'User');
    
       /* Set the subject. */
       $mail->Subject = 'Account Activation';
    
       /* Set the mail message body. */
       $mail->Body = '<!DOCTYPE html>
       <html lang="pl">
       <head>
           <meta charset="UTF-8">
           <meta name="viewport" content="width=device-width, initial-scale=1.0">
           <meta http-equiv="X-UA-Compatible" content="ie=edge">
           <title>Aktywuj Konto</title>
       </head>
       <body style=" margin:0;background: linear-gradient(to right, #0f2027, #203a43, #2c5364); color:white;
       font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif">
           <div class="container">
           <h1 style="text-align:center">Aktywuj swoje konto</h1>
           <div class="content" style="margin-top:100px;">
               <h6 class="text-align:center;">Dziękujemy za rejestrację w aplikacji <a href="lkun2699.nazwa.pl/apk/" style="color:azure;">Aplikacja</a></h6>
           </div>
           <div class="linked" style="background-color: azure;margin:20px;">
               <p style="color:black;text-align:center;">Aby aktywować swoje konto kliknij w poniższy link:</p>
               <p style="color:black;text-align:center;"><a style="color:blue; margin:20px;" href="http://lkun2699.nazwa.pl/apk/activate.php?code='.$kod.'">Aktywuj</a></p>
           </div>
           </div>
       </body>
       </html>';
    
       /* Finally send the mail. */
       $mail->send();
    }
    catch (Exception $e)
    {
       /* PHPMailer exception. */
       echo $e->errorMessage();
    }
    catch (\Exception $e)
    {
       /* PHP exception (note the backslash to select the global namespace Exception class). */
       echo $e->getMessage();
    }
    
    }
    function send_mail_reset($kod, $email) {


        /* If you installed PHPMailer without Composer do this instead: */
        
        require './mail/src/Exception.php';
        require './mail/src/PHPMailer.php';
        require './mail/src/SMTP.php';
    
    
    
        
        
        /* Create a new PHPMailer object. Passing TRUE to the constructor enables exceptions. */
        $mail = new PHPMailer(TRUE);
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        /* Open the try/catch block. */
        try {
           /* Set the mail sender. */
           $mail->setFrom('admin@lkun2699.nazwa.pl', 'Aplikacja');
        
           /* Add a recipient. */
           $mail->addAddress($email, 'User');
        
           /* Set the subject. */
           $mail->Subject = 'Reset Password';
        
           /* Set the mail message body. */
           $mail->Body = '<!DOCTYPE html>
           <html lang="pl">
           <head>
               <meta charset="UTF-8">
               <meta name="viewport" content="width=device-width, initial-scale=1.0">
               <meta http-equiv="X-UA-Compatible" content="ie=edge">
               <title>Aktywuj Konto</title>
           </head>
           <body style=" margin:0;background: linear-gradient(to right, #0f2027, #203a43, #2c5364); color:white;
           font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif">
               <div class="container">
               <h1 style="text-align:center">Zresetuj hasło</h1>
               <div class="content" style="margin-top:100px;">
                   <h6 class="text-align:center;">Jeśli to żądanie nie pochodzi od Ciebie, zignoruj tą wiadomość </h6>
               </div>
               <div class="linked" style="background-color: azure;margin:20px;">
                   <p style="color:black;text-align:center;">Aby zmienić hasło kliknij w poniższy link:</p>
                   <p style="color:black;text-align:center;"><a style="color:blue; margin:20px;" href="http://lkun2699.nazwa.pl/apk/reset.php?code='.$kod.'">Resetuj</a></p>
               </div>
               </div>
           </body>
           </html>';
        
           /* Finally send the mail. */
           $mail->send();
        }
        catch (Exception $e)
        {
           /* PHPMailer exception. */
           echo $e->errorMessage();
        }
        catch (\Exception $e)
        {
           /* PHP exception (note the backslash to select the global namespace Exception class). */
           echo $e->getMessage();
        }
        
        }
    function delete_old($dir,$id) {

 
        //Get a list of all of the file names in the dir.
        $files = glob($dir . '*'.$id.'.*');
         
        //Loop through the file list.
        foreach($files as $file){
            //Make sure that this is a file and not a directory.
            if(is_file($file)){
                //Use the unlink function to delete the file.
               unlink($file);
            }
        }
    }
        function change_avatar($url,$id) {
            include('sql_connect.php');
            $sql = "UPDATE users_ SET avatar = ? WHERE id = ?";
            if($stmt = $mysqli->prepare($sql)) {
                $url = '/'.$url;
                if($stmt->bind_param('ss',$url,$id)) {
                    $stmt->execute();
                    header("Location: index.php");
                } else {
                    die('błąd bindowania '.$mysqli->errno);
                }
            } else {
                die('błąd zapytania '.$mysqli->errno);
            }
            }
            function resize_image($SrcImage,$DestImage)
            {
                   list($iWidth,$iHeight,$type)	= getimagesize($SrcImage);
                $ImageScale          	= 1;
                $NewWidth              	= 200;
                $NewHeight             	= 200;
                $NewCanves             	= imagecreatetruecolor($NewWidth, $NewHeight);
            
                switch(strtolower(image_type_to_mime_type($type)))
                {
                    case 'image/jpeg':
                        $NewImage = imagecreatefromjpeg($SrcImage);
                        break;
                    case 'image/png':
                        $NewImage = imagecreatefrompng($SrcImage);
                        break;
                    case 'image/gif':
                        $NewImage = imagecreatefromgif($SrcImage);
                        break;
                    default:
                        return false;
                }
            
                // Resize Image
                if(imagecopyresampled($NewCanves, $NewImage,0, 0, 0, 0, $NewWidth, $NewHeight, $iWidth, $iHeight))
                {
                    // copy file
                    if(imagejpeg($NewCanves,$SrcImage,80))
                    {
                        imagedestroy($NewCanves);
                        return true;
                    }
                }
            }
            function check_login($last_logged,$ip){
                $time = strtotime($last_logged);
                $end_time = strtotime('+2 minutes', $time);
         
                if(date("Y-m-d H:i:s",$end_time) > date('Y-m-d H:i:s')){
                    if($ip != $_SERVER['REMOTE_ADDR']){
                        echo '<script>location.href="login.php?fail=Błąd autoryzacji"</script>';
                        exit;
                    }
                }
           }
       function update_login($ip,$id){
           require 'sql_connect.php';
           $sql = "UPDATE users_ SET logged = NOW(), ip = ? WHERE id = ?";
           if($stmt = $mysqli->prepare($sql)){
               if($stmt->bind_param('si',$ip,$id)){
                   $stmt->execute();
               }
           }
       }
       function clean_login($id){
        require 'sql_connect.php';
        $sql = "UPDATE users_ SET logged = ?, ip = ? WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $x = NULL;
            if($stmt->bind_param('bbi',$x,$x,$id)){
                $stmt->execute();
            }
        }
    }
    function check_code($code){
        require 'sql_connect.php';
        $sql = "SELECT id FROM users_ WHERE password_reset = ?";
        if($stmt = $mysqli->prepare($sql)){
            if($stmt->bind_param('s',$code)){
                if($stmt->execute()){
                    $result = $stmt->get_result();
                    $row = $result->fetch_row();
           
                    if(count($row) == 1){
                        return true;
                    } else return false;
                }
            }
        } 
    }

        ?>