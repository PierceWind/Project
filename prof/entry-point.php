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

    include ('server.php');

    $userid = $_SESSION['username'];
?>

<!DOCTYPE html>        
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://kit.fontawesome.com/0db0d33132.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelsivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="jquery-3.6.1.js"></script>
        <title>Entry Point</title>  
        <link rel="stylesheet" type="text/css" href="class.css">
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
                        <span class="nav-item"><?php echo $userid; ?></span>
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
                <div class="main-top">
                    <h1 style="margin-left: 38%">Entrance Student Log</h1>
                    <!--<i class="fas fa-user-cog"></i>-->
                </div>
                <div class="tbcontainer" id="tbcontainer">
                    <div class="Filters" id="filters">
                        <i class="fas fa-filter"></i>
                        <span> Fetch results by &nbsp </span>
                        <select name="fetchval" id="fetchval"><!-- AAYUSIN PA KUNG ANO YUNG HAHANAPIN NA CATEGORY-->
                                <option value="" disabled="" selected=""> Student Year </option>
                                <option value="1st Year"> 1st Year </option>
                                <option value="2nd Year"> 2nd Year </option>
                                <option value="3rd Year"> 3rd Year </option>
                                <option value="4th Year"> 4th Year </option>
                        <!--<?php 
                                //foreach ($options2 as $option2) {
                                ?>
                                    <option value=""><?php //echo $option2['year']; ?> </option> query is in server.php-->
                                    <?php 
                                    //}
                                ?>-->
                            </select>
                    </div>
                
                    <br>
                    <table class="table">
                        <thead>
                            <tr>  
                                <th> Student Id </th>
                                <th> Last Name </th>
                                <th> Program </th>
                                <th> Year </th>
                                <th> Time In </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT DISTINCT students.stud_id, stud_lname, stud_class.stud_prog, stud_class.stud_course, stud_class.stud_sec, yandsec.year, entrance_log.logdate, entrance_log.logtime 
                                FROM students
                                INNER JOIN stud_class
                                ON students.stud_id = stud_class.stud_id
                                INNER JOIN yandsec
                                ON yandsec.course =  stud_class.stud_course 
                                INNER JOIN entrance_log 
                                ON students.stud_id = entrance_log.stud_id
                                INNER JOIN teachers 
                                ON teachers.assigned_course = stud_class.stud_course
                                INNER JOIN users 
                                ON users.teacher_id = teachers.teacher_id
                                WHERE users.username = '$userid'";
                            //$query_run = ($db.$query);
                            $query_run = mysqli_query($db, $query);
                            $dash = " - ";

                            if (mysqli_num_rows($query_run) >  0) {
                                foreach($query_run as $row) {
                            ?>

                            <tr>
                                <td><?= $row['stud_id']; ?></td>
                                <td><?= $row['stud_lname']; ?></td>
                                <td><?= $row['stud_prog']; ?> </td>
                                <td><?= $row['year']; ?></td>
                                <td><?= $row['logdate'], $dash, $row['logtime']; ?></td>    
                            </tr>
                        </tbody> 
                    
                            <?php
                                }
                            } else {
                                echo "No Record Found";
                            }
                            ?>
                    </table>
                </div>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#fetchval").on('change',function(){
                            var value = $(this).val();
                            //alert(value);

                            $.ajax({
                                url:"fetch.php",
                                type:"POST",
                                data:'request=' + value;
                                beforeSend:function(){
                                    $(".tbcontainer").html("<span>Working...</span>");
                                },
                                success:function(data){
                                    $(".tbcontainer").html(data);
                                }
                            });
                        });
                    });   
                </script>
            </section>
            </div>
            
        </div>
        
    </body>
</html>