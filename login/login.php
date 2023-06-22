<?php include_once('server.php'); //connect to server ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register & Login Form</title>
        <link rel="stylesheet" type="text/css" href="style.css" media="screen">
    </head>
    <body>
        <div class="col">
        <a class="scan" href = "../scan/entrance.php">
        <img src="scanqr.png" >SCAN ID</a>
            <div class="form-box">
                <img class="an" src = "updatedlogo.png"> 
                <div class="button-box">
                    <div id="btn"></div>
                    <a href="login.php"><button type="button" id="active"  class="toggle-btn" style="color:white">LOGIN</button></a>
                    <a href="register.php"><button type="button"  class="toggle-btn">REGISTER</button></a>
                </div>
                <form method="post" id="login" class="input-group">
                    <?php include('errors.php'); ?>
                    <input type="text" class="input-field" placeholder="Enter Username" name="username" required>
                    <input type="password" class="input-field" placeholder="Enter Password" name="password" required>
                    <br><br><br>
                    <button type="submit" class="submit-btn" name="login_user">CONFIRM</button>
                </form>
            </div>
        </div>
        <script>             
            function getIpAddr(){
            if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ipAddr=$_SERVER['HTTP_CLIENT_IP'];
            }else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ipAddr=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
            $ipAddr=$_SERVER['REMOTE_ADDR'];
            }
            return $ipAddr;
            }
        </script>
        <?php 
            $time=time()-30; // Here you can chnage the attempt lock time. We using 30 here because after 3 failed login attempt, user can't login for 30 second.
            $ip_address=getIpAddr(); // Stroing IP address in a variable.

            $query=mysqli_query($db,"select count(*) as total_count from loginlogs where TryTime > $time and IpAddress='$ip_address'");
            $check_login_row=mysqli_fetch_assoc($query);
            $total_count=$check_login_row['total_count'];
        ?>
    </body>
</html>
