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
    

    if (isset($_POST['course'])) {
    $course = $_POST['course'];
    echo "$course";
    }   
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
        <title>Attendance</title> 
        <link rel="stylesheet" type="text/css" href="report.css">
       
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
            
            <section class="Record">
                <div class="section_top">
                    <h2 style="float: center; margin-left: 40%">Attendance Report</h2>
                </div>
                <div class="tbcontainer" id="tbcontainer">
                    <div class="Filters" id="filter">
                        <form method="post" action="export.php">
                            <input type="submit" name="export" class="btnExport" value="Export" />
                        </form>
                        <i class="fas fa-filter"></i>
                        <span> &nbsp Fetch results by &nbsp </span>
                        <select name="fetchval" id="fetchval" style="margin-top: 1px;"> <!-- AAYUSIN PA KUNG ANO YUNG HAHANAPIN NA CATEGORY-->
                            <option value="" disabled="" selected="">Student Class</option>
                            <option value="Student Id"> Id </option>
                            <option value="Student Name"> Student name </option>
                            <option value="Year"> Year </option>
                            <option value="Course"> Course </option>
                            </select>
                            </button> 
                    </div> 

                    <br><br>

                    <table class="table">
                        <thead>
                            <tr> 
                                <th> Student Id </th>
                                <th> Student Last Name </th>
                                <th> Class </th>
                                <th> Date </th>
                                <th> Status </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT students.stud_id, students.stud_lname, stud_class.stud_course, timein.status, timein.log_date
                                FROM students
                                INNER JOIN stud_class 
                                ON students.stud_id = stud_class.stud_id
                                INNER JOIN entrance_log 
                                ON students.stud_id = entrance_log.stud_id
                                INNER JOIN timein 
                                ON students.stud_id = timein.stud_id 
                                INNER JOIN timeout 
                                ON students.stud_id = timeout.stud_id
                                INNER JOIN teachers 
                                ON teachers.assigned_course = stud_class.stud_course
                                INNER JOIN users 
                                ON users.teacher_id = teachers.teacher_id
                                WHERE users.username = '$userid'";
                                $result = mysqli_query($db, $query);

                                $q1 = "SELECT COUNT(status) AS [NumPresent]
                                FROM timein WHERE status='Present'";

                                $q2 = "SELECT COUNT(status) AS [NumLate]
                                FROM timein WHERE status='Late'";

                                $q3 = "SELECT COUNT(status) AS [NumAbsent]
                                FROM timein WHERE status='Absent'";

                                $q4 = "SELECT COUNT(status) AS [NumCutting]
                                FROM timein WHERE status='Cutting'";

                                if (mysqli_num_rows($result) >  0) {
                                    foreach($result as $row) {
                                ?>
    
                                <tr>
                                    <td><?= $row['stud_id']; ?></td>
                                    <td><?= $row['stud_lname']; ?></td>
                                    <td><?= $row['stud_course']; ?> </td>
                                    <td><?= $row['log_date']; ?></td>
                                    <td><?= $row['status']; ?></td>    
                                </tr>
                            </tbody> 
                        
                                <?php
                                    }
                                }
                                ?>
                               
                    </table>
                   
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
            </div>  
        </div>  
    </body>
</html>