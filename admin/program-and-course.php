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
?>

<?php include_once('server.php'); //connect to server

//initializing variables
$prog_name ="";
$course_code ="";
$course_name ="";
$prog ="";
$prog1 ="";
$course1 ="";
$year ="";
$section ="";
$success = array();
$errors = array();


//ADD PROGRAM 
if (isset($_POST['add_program'])) {
    //receive all input values from the form
    $prog_name = mysqli_real_escape_string($db, $_POST['prog_name']);
    
    //form validation: ensure that the form is correctly filled...
    //by adding (array_push()) corresponding error unto $errors1 array
    if (empty($prog_name)) {array_push($errors, "Program Name is required");}
    

    //first check the database to make sure 
    //a program does not actually exist 
    $program_check_query = "SELECT * FROM program WHERE prog_name='$prog_name' LIMIT 1";
    $result = mysqli_query($db, $program_check_query);
    $program = mysqli_fetch_assoc($result);

    if ($program) { //exists
        if ($program['prog_name']==$prog_name) {
            array_push($errors, "Program already exists");
        }
    }

    //finally, register user if there are no errors in the form 
    if (count($errors) == 0 ) {
        $query =    "INSERT INTO program (prog_name)
                    VALUES ('$prog_name')";
        mysqli_query($db, $query);
        array_push($success, "You successfully added the Program", $prog_name);
    }
}

//ADD COURSE 
if (isset($_POST['add_course'])) {
    //receive all input values from the form
    $course_code = mysqli_real_escape_string($db, $_POST['course_code']);
    $course_name = mysqli_real_escape_string($db, $_POST['course_name']);
    $prog = mysqli_real_escape_string($db, $_POST['prog']);
    
    //form validation: ensure that the form is correctly filled...
    //by adding (array_push()) corresponding error unto $errors array
    if (empty($course_code)) {array_push($errors, "Course Code is required");}
    if (empty($course_name)) {array_push($errors, "Course Name is required");}
    if (empty($prog)) {array_push($errors, "Please Select a Program");}
    

    //first check the database to make sure 
    //a course does not actually exist 
    $course_check_query = "SELECT * FROM course WHERE course_code='$course_code' AND course_name='$course_name' LIMIT 1";
    $result2 = mysqli_query($db, $course_check_query);
    $course = mysqli_fetch_assoc($result2);

    if ($course) { //exists
        if ($course['course_code']==$course_code) {
            array_push($errors, "Course already exists");
        }
    }

    //finally, register user if there are no errors in the form 
    if (count($errors) == 0 ) {
        $query2 =    "INSERT INTO course (course_code, course_name, prog)
                    VALUES ('$course_code', '$course_name', '$prog')";
        mysqli_query($db, $query2);
        $_SESSION['success'] = "You successfully added the Course";
    }
}


//ADD SECTION 
if (isset($_POST['add_section'])) {
    //receive all input values from the form
    $prog1 = mysqli_real_escape_string($db, $_POST['prog1']);
    $course1 = mysqli_real_escape_string($db, $_POST['course1']);
    $year = mysqli_real_escape_string($db, $_POST['year']);
    $section = mysqli_real_escape_string($db, $_POST['section']);
    
    //form validation: ensure that the form is correctly filled...
    //by adding (array_push()) corresponding error unto $errors array
    if (empty($prog1)) {array_push($errors, "Please Select a Program");}
    if (empty($course1)) {array_push($errors, "Please Select a Course");}
    if (empty($year)) {array_push($errors, "Please Select Year");}
    if (empty($section)) {array_push($errors, "Section is required");}
    

    //first check the database to make sure 
    //a course does not actually exist 
    $section_check_query = "SELECT * FROM yandsec WHERE year='$year' AND section='$section' LIMIT 1";
    $result3 = mysqli_query($db, $section_check_query);
    $sec = mysqli_fetch_assoc($result3);

    if ($sec) { //exists
        if ($sec['section']==$section) {
            array_push($errors, "Section already exists");
        }
    }

    //finally, register user if there are no errors in the form 
    if (count($errors) == 0 ) {
        $query3 =    "INSERT INTO yandsec (program, course, year, section)
                    VALUES ('$prog1', '$course1', '$year', '$section')";
        mysqli_query($db, $query3);
        $_SESSION['success'] = "You successfully added the Section";
        $_SESSION['section'] = $section; 
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
        <title>Program & Course</title>
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
                    <h1>Add Program</h1>
                </div>
                <br>
                <div class="sec1">
                    <form method="post" id="program" class="input-group">
                    <?php include_once('errors.php'); ?>
                    <?php include_once('success.php'); ?>
                        <label for="prog_name">Program Name</label><br>
                        <input type="text" id="prog_name" name="prog_name" placeholder="Program Name (e.g. Bachelor of Science in Information System)" value="<?php echo $prog_name;?>" required>
                        <br><br>
                        <button type="submit" class="submit-btn" name="add_program">Submit</button>
                    </form> 
                </div>
                <br><br>

                <div class="main-top">
                    <h1>Add Course</h1>
                </div>
                <br>
                <div class="sec1">
                    <form method="post" id="course" class="input-group">
                    <?php include_once('errors.php'); ?>
                    <?php include_once('success.php'); ?>
                        <label for="course">Course Code</label><br>
                        <input type="text" id="course_code" name="course_code" placeholder="Course Code (e.g. IS104)" value="<?php echo $course_code;?>" required>
                        <label for="course">Course Name</label><br>
                        <input type="text" id="course_name" name="course_name" placeholder="Course Name (e.g. System Analysis and Design)" value="<?php echo $course_name;?>" required>
                        <label for="course">Program</label><br>
                        <select name="prog">
                            <?php 
                            foreach ($options as $option) {
                            ?>
                                <option><?php echo $option['prog_name']; ?> </option>
                                <?php 
                                }
                            ?>
                        </select>
                        <br><br>
                        <button type="submit" class="submit-btn" name="add_course">Submit</button>
                    </form> 
                </div>
                <br><br>
                <div class="main-top">
                    <h1>Add Section</h1>
                </div>
                <br>
                <div class="sec1">
                    <form method="post" id="yANDsec" class="input-group">
                    <?php include_once('errors.php'); ?>
                    <?php include_once('success.php'); ?>
                        <label for="yANDsec">Select Program</label><br>
                            <select name="prog1">
                                <?php 
                                foreach ($options as $option) {
                                ?>
                                    <option><?php echo $option['prog_name']; ?> </option>
                                    <?php 
                                    }
                                ?>
                            </select>
                        <label for="yANDsec">Select Course</label><br>
                            <select name="course1">
                                <?php 
                                foreach ($options1 as $option1) {
                                ?>
                                    <option><?php echo $option1['course_name']; ?> </option>
                                    <?php 
                                    }
                                ?>
                            </select>
                            
                        <label for="yANDsec">Select Year</label><br>
                            <select name="year">
                                <option>1st Year</option>
                                <option>2nd Year</option>
                                <option>3rd Year</option>
                                <option>4th Year</option>
                            </select>
                        <label for="yANDsec">Section</label><br>
                        <input type="text" id="prog_name" name="section" placeholder="Section (e.g. 01 or Macintosh)" value="<?php echo $section;?>" required>
                        <br><br>
                        <button type="submit" class="submit-btn" name="add_section">Submit</button>
                    </form> 
                </div>
                <br><br><br><hr><br><br>
                <section class="view" id="view">
                    <div class="view-list">
                        <h1>View Record</h1>                    
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Program</th>
                                        <th>Course</th>
                                        <th>Year</th>
                                        <th>Section</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $query = "SELECT * FROM yandsec  ORDER BY date_created DESC LIMIT 5";
                                        $query_run = mysqli_query($db, $query);

                                        if(mysqli_num_rows($query_run) > 0) {
                                            foreach($query_run as $progcourse) {
                                            ?>
                                        <tr>
                                            <td><?= $progcourse['program']; ?></td>
                                            <td><?= $progcourse['course']; ?></td>
                                            <td><?= $progcourse['year']; ?></td>
                                            <td><?= $progcourse['section']; ?></td>
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