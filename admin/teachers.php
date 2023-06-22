<?php 
    sleep(1);
    session_start();

    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must     log in first";
        header('location: ../login/login.php');
    }
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header('location: ../login/login.php');
    }
?>

<?php include_once('server.php'); //connect to server
//initializing variables
$teacher_id ="";
$teacher_fname ="";
$teacher_fname ="";
$teacher_mname ="";
$teacher_lname ="";
$assigned_course="";
$assigned_section ="";
$assigned_sched="";
$success = array();
$errors = array();

//ADD TEACHER 
if (isset($_POST['add_teacher'])) {
    //receive all input values from the form
    $teacher_id = mysqli_real_escape_string($db, $_POST['teacher_id']);
    $teacher_fname = mysqli_real_escape_string($db, $_POST['teacher_fname']);
    $teacher_mname = mysqli_real_escape_string($db, $_POST['teacher_mname']);
    $teacher_lname = mysqli_real_escape_string($db, $_POST['teacher_lname']);
    $assigned_program = mysqli_real_escape_string($db, $_POST['assigned_program']);
    $assigned_course = mysqli_real_escape_string($db, $_POST['assigned_course']);
    $assigned_section = mysqli_real_escape_string($db, $_POST['assigned_section']);
    $assigned_sched = mysqli_real_escape_string($db, $_POST['assigned_sched']);

    //form validation: ensure that the form is correctly filled...
    //by adding (array_push()) corresponding error unto $errors array
    if (empty($teacher_id)) {array_push($errors, "Teacher ID Number is Required");}
    if (empty($teacher_fname)) {array_push($errors, "First Name is Required");}
    if (empty($teacher_lname)) {array_push($errors, "Last Name is Required");}
    if (empty($assigned_program)) {array_push($errors, "Please Assign Program");}
    if (empty($assigned_course)) {array_push($errors, "Please Assign Course");}
    if (empty($assigned_section)) {array_push($errors, "Please Assign Section");}
    if (empty($assigned_sched)) {array_push($errors, "Please Assign a Schedule");}
    
    //first check the database to make sure 
    //a course does not actually exist 
    $teacher_check_query = "SELECT * FROM teachers WHERE teacher_id='$teacher_id' AND assigned_program='$teacher_lname' LIMIT 1";
    $result4 = mysqli_query($db, $teacher_check_query);
    $teacher = mysqli_fetch_assoc($result4);

    if ($teacher) { //exists
        if ($teacher['teacher_id']==$teacher_id) {
            array_push($errors, "Teacher already exists");
        }
    }

    //finally, register user if there are no errors in the form 
    if (count($errors) == 0 ) {
        $query4 =    "INSERT INTO teachers (teacher_id, teacher_fname, teacher_mname, teacher_lname, assigned_program, assigned_course, assigned_section)
                    VALUES ('$teacher_id', '$teacher_fname', '$teacher_mname', '$teacher_lname', '$assigned_program', '$assigned_course', '$assigned_section')";
        mysqli_query($db, $query4);
        $_SESSION['success'] = "You successfully added a Teacher";
        $_SESSION['teacher_id'] = $teacher_id; 
    }

    $sched_check_query = "SELECT * FROM schedule WHERE teacher_id='$teacher_id' AND assigned_program='$assigned_program' AND  assigned_course='$assigned_course' AND assigned_section='$assigned_section' AND assigned_sched='$assigned_sched' LIMIT 1";
    $result5 = mysqli_query($db, $sched_check_query);
    $sched = mysqli_fetch_assoc($result5);

    if ($sched) { //exists
        if ($sched['teacher_id']==$teacher_id) {
            array_push($errors, $teacher_id, " is already occupied with a class");
        }
    }

    //finally, teachers sched if there are no errors in the form 
    if (count($errors) == 0 ) {
        $query5 =    "INSERT INTO schedule (teacher_id, assigned_program, assigned_course, assigned_section, assigned_sched)
                    VALUES ('$teacher_id', '$assigned_program', '$assigned_course', '$assigned_section', '$assigned_sched')";
        mysqli_query($db, $query5);
        $_SESSION['success'] = "You successfully added a Schedule";
    }
}       
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://kit.fontawesome.com/0db0d33132.js" crossorigin="anonymous"></script>
        <title>Teacher</title>
        <link rel="stylesheet" type="text/css" href="program-and-course.css">

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
                    <hr>
                    <br>
                    <li><a href="dashboard.php"><i class="fas fa-house-user"></i>
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
                    <h1>Add Teacher</h1>
                </div>
                <br>    
                <div class="sec1">
                    <form method="post" id="teachers" class="input-group">
                        <?php include_once('errors.php'); ?>
                        <?php include_once('success.php'); ?>
                        <label for="teacher_id">ID Number</label><br>
                        <input type="text" id="teacher" name="teacher_id" placeholder="ID number" value="<?php echo $teacher_id;?>" required><br>
                        <label for="teacher_name">Name</label><br>
                        <input type="text" id="teacher_fname" name="teacher_fname" placeholder="First Name" value="<?php echo $teacher_fname;?>" required>
                        <input type="text" id="teacher_mname" name="teacher_mname" placeholder="Middle Name" value="<?php echo $teacher_mname;?>">
                        <input type="text" id="teacher_lname" name="teacher_lname" placeholder="Last Name" value="<?php echo $teacher_lname;?>" required>
                        <label for="teachers">Assign Program</label><br>
                            <select name="assigned_program">
                                <?php 
                                foreach ($options as $option) {
                                ?>
                                    <option> <?php echo $option['prog_name']; ?> </option>
                                    <?php 
                                    }
                                ?>
                            </select>
                        <label for="teacher">Assign Course</label><br>
                            <select name="assigned_course">
                                <?php 
                                foreach ($options1 as $option1) {
                                ?>
                                    <option><?php  echo $option1['course_code']; ?> </option>
                                    <?php 
                                    }
                                ?>
                            </select>
                        <label for="teachers">Assign Section</label><br>
                            <select name="assigned_section">
                                <?php 
                                foreach ($options2 as $option2) {
                                ?>
                                    <option><?php echo $option2['section']; ?> </option>
                                    <?php 
                                    }
                                ?>
                            </select>
                            <label for="">Assign a Schedule</label><br>
                            <input type="time" class=" input-group" id="assigned_sched" name="assigned_sched" required>
                        <br><br>
                        <button type="submit" class="submit-btn" name="add_teacher">Submit</button>
                    </form> 
                </div>

            
                <br><br><br><hr><br><br>
                <section class="view" id="view">
                    <div class="view-list">
                        <h1>View Record</h1>                    
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID Number</th>
                                        <th>Name</th>
                                        <th>Assigned Class</th>
                                        <th>Schedule</th>
                                    </tr>
                                </thead>
                                <tbody> <br>
                                    <?php 
                                        $query = "SELECT teachers.teacher_id, teacher_fname, teacher_mname, teacher_lname, teachers.assigned_program, teachers.assigned_course, teachers.assigned_section, schedule.assigned_sched 
                                        FROM teachers 
                                        INNER JOIN schedule ON teachers.teacher_id = schedule.teacher_id;";
                                        $query_run = mysqli_query($db, $query);
                                        $space = " ";
                                        $dash = " - ";

                                        if(mysqli_num_rows($query_run) > 0) {
                                            foreach($query_run as $progcourse) {
                                            ?>
                                        <tr>
                                            <td><?= $progcourse['teacher_id']; ?></td>
                                            <td><?= $progcourse['teacher_fname'], $space, $progcourse['teacher_mname'], $space, $progcourse['teacher_lname']; ?></td>
                                            <td><?= $progcourse['assigned_program'], $dash, $progcourse['assigned_course'], $dash, $progcourse['assigned_section']; ?></td>
                                            <td><?= $progcourse['assigned_sched']; ?></td>
                                        </tr>
                                        <?php
                                            }
                                        }
                                        else {
                                            echo "<h5> No Record Found </h5>";
                                        }
                                    ?>
                                                            
                                </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </div>
    </body>
</html>