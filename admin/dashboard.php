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
                    <li><a href="" style="cursor:auto;"><i class="fas fa-people-roof"></i>
                        <span class="nav-item">Administrator</span>
                    </a></li> 
                    <hr style="border: 1px solid blue;">
                    <br>
                    <li><a href=" dashboard.php"><i class="fas fa-house-user"></i>
                        <span class="nav-item">Home</span>
                    </a></li>
                    <a href="program-and-course.php">
                        <i class="fas fa-book"></i>
                        <span class="nav-item">Program & Course</span>
                    </a></li>
                    <a href="teachers.php">
                    <i class="fas fa-chalkboard-user"></i>
                        <span class="nav-item">Manage Teachers</span>
                    </a></li>
                    <li><a href="students.php"><i class="fas fa-id-card"></i>
                        <span class="nav-item">Manage Students</span>
                    </a></li>
                    <li><a href="database.php">
                        <i class="fas fa-database"></i>
                        <span class="nav-item">Database</span>
                      </a></li>
                    <li><a href="dashboard.php?logout='1'" class="logout"><i class="fas fa-sign-out-alt"></i>
                        <span class="nav-item">Log out</span>
                    </a></li>
                </ul>
            </nav>

            <section class="main">
               <div class="main-top">
                    <h2> Admin Dashboard</h2>
               </div>
               <div class="top-box">
                <?php 
                    $query3 = "SELECT DISTINCT (teacher_id) FROM teachers WHERE teacher_id <> 0";
                    $result3 = mysqli_query($db, $query3);
                    $row3 = mysqli_num_rows($result3);

                    $query2 = "SELECT DISTINCT(course_code) FROM course";
                    $result2 = mysqli_query($db, $query2);
                    $row2 = mysqli_num_rows($result2);

                    $query1 = "SELECT DISTINCT(stud_id) FROM students";
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
                    <div class="boxes">
                        <span>Number of Teachers</span>  <br> <br>
                        <b> <?php echo $row3; ?> Teachers</b>
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
                                    $sql="SELECT * FROM timein";//need palitan dbname
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
                        $query3 = "SELECT DISTINCT (stud_id) FROM students WHERE  stud_id  NOT IN (SELECT stud_id FROM timein) LIMIT 5";
                        $result3 = mysqli_query($db, $query3);
                        $row3 = mysqli_num_rows($result3);

                        $query2 = "SELECT DISTINCT (stud_id) FROM timein WHERE status='Tardy' LIMIT 5";
                        $result2 = mysqli_query($db, $query2);
                        $row2 = mysqli_num_rows($result2);

                        $query1 = "SELECT DISTINCT (stud_id) FROM timein WHERE status='Cutting' LIMIT 5";
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
                        <h3>TOP 5 Tardy Students </h3> <br>
                            <?php if($row2 > 0) {
                                while ($res = mysqli_fetch_array($result2)) {
                                    echo "<li>".$res['stud_id']."</li>";
                                    echo "<br>";
                                }
                            }
                            ?>
                    </div>
                    <div class="boxes">
                        <h3>TOP 5 Cutting Students</h3> <br>
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