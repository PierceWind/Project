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

    $userid = $_SESSION['username'];
    $query ="SELECT assigned_course 
        FROM teachers 
        INNER JOIN users 
        ON users.teacher_id = teachers.teacher_id 
        WHERE users.username = '$userid'";
        $result = $db->query($query);
        $course= (mysqli_fetch_all($result, MYSQLI_ASSOC));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://kit.fontawesome.com/0db0d33132.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.36.3/apexcharts.min.js"></script>
        <script src="calendar.js" defer></script>
        <script src="bargraph.js" defer></script>
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="dashboard.css">
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
               <div class="main-top">
                    <h2> Professor Dashboard</h2>
               </div>
               <div class="top-box">
                <?php 
                    $query2 = "SELECT DISTINCT (course_code) FROM course
                        INNER JOIN teachers 
                        ON teachers.assigned_course = course.course_code
                        INNER JOIN users 
                        ON users.teacher_id = teachers.teacher_id
                        WHERE users.username = '$userid'";
                    $result2 = mysqli_query($db, $query2);
                    $row2 = mysqli_num_rows($result2);

                    $query1 = "SELECT DISTINCT (stud_id) FROM stud_class
                        INNER JOIN teachers 
                        ON teachers.assigned_course = stud_class.stud_course
                        INNER JOIN users 
                        ON users.teacher_id = teachers.teacher_id
                        WHERE users.username = '$userid'";
                    $result1 = mysqli_query($db, $query1);
                    $row1 = mysqli_num_rows($result1);
                ?>
                    <div class="boxes">
                        <span>Number of Students</span>  <br> <br>
                        <b> <?php echo $row1; ?> Students</b> 
                    </div>
                    <div class="boxes">
                        <span>Number of Classes</span>  <br> <br>
                        <b> <?php echo $row2; ?> Classes</b> 
                    </div>
                </div>   

                <div class="graphcal">
                    <div class="cal">
                        <h3> Calendar</h3>
                        <header>
                        <p class="current-date"></p>
                        <div class="icons">
                            <i id="prev"class="fas fa-chevron-left"></i>
                            <i id="next" class="fas fa-chevron-right"></i>
                        </div>
                        </header>
                        <div class="calendar">
                            <ul class="weeks">
                                <li>Sun</li>
                                <li>Mon</li>
                                <li>Tue</li>
                                <li>Wed</li>
                                <li>Thur</li>
                                <li>Fri</li>
                                <li>Sat</li>
                            </ul>
                            <ul class="days"></ul>
                        </div>
                    </div>
                    <div class="bar" id="bar-chart">
                        <h3 style="margin-left: 35%;"> Student Status Report</h3>
                         
                            <?php
                                //attempt select query execution
                                try{
                                    $sql="SELECT status FROM timein
                                        INNER JOIN stud_class
                                        ON stud_class.stud_id = timein.stud_id
                                        INNER JOIN teachers 
                                        ON teachers.assigned_course = stud_class.stud_course
                                        INNER JOIN users 
                                        ON users.teacher_id = teachers.teacher_id
                                        WHERE users.username = '$userid'";
                                    $result= $db->query($sql);
                                    if( mysqli_num_rows($result)> 0) {
                                        $colname = array();
                                        
                                        while($row = mysqli_fetch_array($result)){ 
                                            $colname[] = $row["status"];
                                        }
                                    unset($result);
                                    }else{
                                        echo"no record match";
                                    }
                                }catch(PDOException $e){
                                    die("error: cant execute". $e->getMessage());
                                }
                                //close connection
                                unset($pdo);
                            ?>
                    </div>
                </div>
                <br>

                
                <div class="bottom-box">
                    <?php   
                        //$query = ("SELECT *
                        //FROM ( SELECT status from timein )
                        //PIVOT ( count(*) for status in ('Present', 'Absent', 'Tardy', 'Cutting'))");
                    ?>

                    <?php 
                        $query3 = "SELECT DISTINCT students.stud_id, COUNT(timein.stud_id) AS numofpres FROM students 
                            INNER JOIN timein
                            ON students.stud_id = timein.stud_id 
                            INNER JOIN stud_class
                            ON students.stud_id = stud_class.stud_id
                            INNER JOIN teachers 
                            ON teachers.assigned_course = stud_class.stud_course
                            INNER JOIN schedule 
                            ON teachers.teacher_id = schedule.teacher_id
                            INNER JOIN users 
                            ON users.teacher_id = teachers.teacher_id
                            WHERE users.username = '$userid' AND timein.stud_id IS NULL  
                            ORDER BY numofpres ASC LIMIT 5"; //class_code IN ({implode(',', $course)})' OR timein.stud IN students.stud_id
                        $result3 = mysqli_query($db, $query3);
                        $row3 = mysqli_num_rows($result3);

                        $query2 = "SELECT DISTINCT timein.stud_id FROM timein 
                            INNER JOIN stud_class
                            ON timein.stud_id = stud_class.stud_id
                            INNER JOIN teachers 
                            ON teachers.assigned_course = stud_class.stud_course
                            INNER JOIN users 
                            ON users.teacher_id = teachers.teacher_id
                            WHERE users.username = '$userid' AND status='Tardy' LIMIT 5";
                        $result2 = mysqli_query($db, $query2);
                        $row2 = mysqli_num_rows($result2);

                        $query1 = "SELECT DISTINCT timein.stud_id FROM timein 
                            INNER JOIN stud_class
                            ON timein.stud_id = stud_class.stud_id
                            INNER JOIN teachers 
                            ON teachers.assigned_course = stud_class.stud_course
                            INNER JOIN users 
                            ON users.teacher_id = teachers.teacher_id
                            WHERE users.username = '$userid' AND status='Cutting' LIMIT 5";
                        $result1 = mysqli_query($db, $query1);
                        $row1 = mysqli_num_rows($result1);
                    ?>
                    <div class="boxes">
                        <h3>TOP 5 Absentee</h3> <br>
                            <?php if($row3 > 0) {
                                while ($res = mysqli_fetch_array($result3)) {
                                    echo "<li>".$res['stud_id']."</li>";
                                    echo "<br>";
                                }
                            }
                            ?>
                    </div>
                    <div class="boxes">
                        <h3>TOP 5 Tardy</h3> <br>
                            <?php if($row2 > 0) {
                                while ($res = mysqli_fetch_array($result2)) {
                                    echo "<li>".$res['stud_id']."</li>";
                                    echo "<br>";
                                }
                            }
                            ?>
                    </div>
                    <div class="boxes">
                        <h3>TOP 5 Cutting</h3> <br>
                            <?php if($row1 > 0) {
                                while ($res = mysqli_fetch_array($result1)) {
                                    echo "<li>".$res['stud_id']."</li>";
                                    echo "<br>";
                                }
                            }
                            ?> 
                    </div>
                </div>   
            </section>
        </div>
    </body>
</html>