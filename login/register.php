<?php include('server.php') //connect to server ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register & Login Form</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="col">
        <a class="scan" href = "../scan/entrance.php">
        <img src="scanqr.png" >SCAN ID</a>
            <div class="form-box">
                <img class="an" src = "updatedlogo.png"> 
                <div class="button-box">
                    <div id="btn" style="left:110px"></div>    
                    <a href="login.php"><button type="button" id="" class="toggle-btn">LOGIN</button></a>
                    <a href="register.php"><button type="button" id="active"  class="toggle-btn" style="color:white">REGISTER</button></a>
                </div>
                <form method="post" id="register" class="input-group">
                    <?php include('errors.php'); ?>
                    <input type="text" class="input-field" name="teacher_id" placeholder="Enter Teacher ID Number" value="<?php echo $teacher_id;?>" required>
                    <input type="text" class="input-field" name="username" placeholder="Enter Username" value="<?php echo $username;?>" required>
                    <input type="email" class="input-field" name="email" placeholder="Enter Email Address " value="<?php echo $email;?>" required>
                    <input type="password" class="input-field" name="password_1" placeholder="Enter New Password" value="<?php echo $password_1;?>" required>
                    <input type="password" class="input-field" name="password_2" placeholder="Enter New Password Again" value="<?php echo $password_2;?>" required>
                    <br><br>
                    <button type="submit" class="submit-btn" name="reg_user">SIGN UP</button>
                </form> 
            </div>
        </div>
    </body>
</html>
