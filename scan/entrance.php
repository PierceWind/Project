<?php 
  sleep(1);
  include ('server.php');
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
        <script src="ht.js"></script>
        <title>Attendance</title> 
        <link rel="stylesheet" type="text/css" href="style.css">
       
    </head>
    <body>
        <br>
        <div>
            <a href="../login/login.php" style=" text-decoration: none;"><button type="button" class="back" style="float:center;">Back</button></a>
        </div>
        <br>
        <section class="main">
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
                                        
                                       <br> 
                                        <div class="studentname" style="margin-left:-120px;">
                                            <label for="ID">Student Number</label><br>
                                            <input type="text" name="start" class="input" id="result" onkeyup="showHint(this.value)" placeholder="Student Number" readonly="" name='id'/>

                                            <br><br>
                                        <div class="studentId">
                                            <span id="txtHint"></span>
                                        </div>
                                        </div>

                                        <!---
                                        <div class="classsub">
                                            <label for="class">Class</label><br>
                                            <input type="text" name="start" class="input" id="result" onkeyup="showHint(this.value)" placeholder="Class" readonly="" />
                                        </div>-->
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
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        
            <script type="text/javascript">
                var x = document.getElementById("myAudio1");
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
                    xmlhttp.open("GET", "gethint.php?id=" + str, true);
                    xmlhttp.send();
                    }
                }
                
                function playAudio() { 
                    x.play(); 
                }     

            </script>
    </body>
</html>