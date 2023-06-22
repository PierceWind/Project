<?php 
    sleep(1);
    session_start();

    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: ../login/login.php');
    }
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header('location: ../login/login.php');
    }

    require('server.php');
    include('fetchcourse.php');

    $userid = $_SESSION['username'];
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://kit.fontawesome.com/0db0d33132.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="ht.js"></script>
        <script type="text/javascript" src= "attendance.js">
        </script>
        <title>Attendance</title> 
        <link rel="stylesheet" type="text/css" href="attendance.css">
       
    </head>
    <body>
        <div class="container">
            <nav>
                <ul>
                    <br>
                    <li><a href="dashboard.php" class="logo">
                        <img src="../login/updatedlogo.png" alt="">
                        <span class="nav-item">DFCAMCLP<br>IT Campus</span>
                        </a></li> <br>
                    <li><a href="" style="cursor:auto;"><i class="fas fa-user-tie"></i>
                        <span class="nav-item"><?php echo $userid; ?> </span>
                    </a></li> 
                    <hr style="border: 1px solid blue;">
                    <br>
                    <li><a href="dashboard.php"><i class="fas fa-house-user"></i>
                        <span class="nav-item">Home</span>
                    </a></li>
                    <a href=" class.php"><i class="fas fa-book"></i>
                        <span class="nav-item">Class</span>
                    </a></li>
                    <a href="entry-point.php"><i class="fas fa-right-to-bracket"></i>
                        <span class="nav-item">Entry Point</span>
                    </a></li>
                    <li><a href="attendance.php"><i class="fas fa-id-card"></i>
                        <span class="nav-item">Attendance</span>
                    </a></li>
                    <li><a href="report.php">
                        <i class="fas fa-database"></i>
                        <span class="nav-item">Report</span>
                      </a></li>
                    <li><a href="dashboard.php?logout=''" class="logout"><i class="fas fa-sign-out-alt"></i>
                        <span class="nav-item">Log out</span>
                    </a></li>
                </ul>
            </nav>
            
            <section class="main">
            <h1 style="margin-left:38%;">Class Attendance - Time In</h1>
                <div class="main-top">
                <form method="post" id="" class="input-group" action="#">
                    <label style="float: left; margin-left:20px; margin-top: 15px;" for="course"><strong>Course:</strong></label>
                    <select name="cs" id="cs" style="margin-left: 30px; margin-right: 10px; margin-top: 10px; width: 100px; padding: 5px;">
                        <?php 
                        foreach ($options as $row) {
                            $course = $row['assigned_course'];
                            $option.='<option value="'.$course.'">'.$course.'</option>';
                            
                        ?>
                            <?php echo $option; 
                            }
                            
                        ?>
                    </select>
                    <input type="submit" value="submit" style = "margin-right: 30px; background-color: yellow; padding: 5px; border-radius: 5px;" onclick="sessionStorage.setItem('value', document.getElementById('cs').value);"/> <!--attendance.js-->
                </form> 
                    
                    <a style="" href="../timeout/timeout.php"> <i style="margin-right: 50px" class="fas fa-square-check"> TIME OUT</i></a>
                </div>
                <br>

                <div class="row">
                    <div class="column">
                        <div class="card">
                            <br>
                            <h2>QR Code Scanner</h2><br>
                            <div class="container">
                                <video id="preview" width=100%></video>
                                <div class="col">
                                    <div class="reader"id="reader"></div>
                                    <audio id="myAudio1">
                                        <source src="success.mp3" type="audio/ogg">
                                    </audio>
                                    <audio id="myAudio2">
                                        <source src="failes.mp3" type="audio/ogg">
                                    </audio>
                                    <Script>
                                        function onScanSuccess(qrCodeMessage) {
                                            document.getElementById("result").value = qrCodeMessage;
                                            showHint(qrCodeMessage);
                                        playAudio();
                                        
                                        }
                                        function onScanError(errorMessage) {
                                            //handle scan error
                                          }
                                          var html5QrcodeScanner = new Html5QrcodeScanner(
                                              "reader", { fps: 10, qrbox: 250 });
                                          html5QrcodeScanner.render(onScanSuccess, onScanError);
                                    </Script>
                                    </div>
                            </div>
                        </div>
                           
                        
                    </div>
                    
                    <div class="column">
                        <div class="card">
                            <br>
                            <div class="row">
                            <h2>Student Verification</h2><br>
                            <div class="container">
                                <div class="result">
                                    <div id="grid">
                                        <!--<div class="pic">
                                            <file  type="hidden" class="pic" id="pic" onkeyup="showHint(this.value)">
                                        </div>-->
                                        <br>
                                        <div class="studentname" style="margin-left:-125px;">
                                            <label for="ID">Student Number</label><br>
                                            <input type="text" name="start" class="input" id="result" onkeyup="showHint(this.value)" placeholder="Student Number" readonly="" name='id'/>
                                        <div class="studentId">
                                            <span id="txtHint"></span>
                                        </div>
                                        </div>
                                       

                                    </div>
                                   <!-- <div class="profile-sidebar"></div>
                                        <img src="#" class="pic" id="pic" onkeyup="showHint(this.value)">
                                        <ul>
                                            <li>
                                                <input type="text" name="start" class="input" id="result" onkeyup="showHint(this.value)" placeholder="Student Id" readonly="" />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="profile-main">
                                        <p class="profile-name">
                                            <input type="text" name="start" class="input" id="result" onkeyup="showHint(this.value)" placeholder="result here" readonly="" />
                                        </p>
                                        <p class="profile-body"> is present in you class</p>
                                        
                                    </div>
                          
                                <p>Status: <span id="txtHint"></span></p>
                                </div>-->
                                
                                <!-- 
                                            dun sa may onclick sa taas, nag assign ako ng value sa sessionStorage diba. nasave siya sa "value"
                                            ngayon kung nasave siya sa value, kinuha ko siya dito sa baba.
                            -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        
            <script type="text/javascript">
                var x = document.getElementById("myAudio1");
                var ccs = sessionStorage.getItem("value"); // dito oki 
                    var x2 = document.getElementById("myAudio2");      
                    function showHint(str) {
                        if (str.length == 0) {
                        document.getElementById("txtHint").innerHTML = "";
                        return;
                        } else {
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                            }
                        };
                        xmlhttp.open("GET", "gethint.php?id=" + str + "&course=" + ccs, true); // tapos sinama ko siya sa parameter. ahhhh hahahahha nyemass ng naencounter ko pa lang is yung document.eneme hahahahaha okay lang yan. congrats. meron a baproblem?
                        xmlhttp.send();
                        }
                    }
                    
                    function playAudio() { 
                        x.play(); 
                    }  
            

            </script>
    </div>
    </body>
</html>