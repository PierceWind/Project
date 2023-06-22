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

<?php require('server.php'); //connect to server

//UPDATE STUDENT 
if (isset($_POST['update_student'])) {
    //receive all input values from the form
    $id = mysqli_real_escape_string($db, $_POST['stud_id']);
    $stud_fname = mysqli_real_escape_string($db, $_POST['stud_fname']);
    $stud_mname = mysqli_real_escape_string($db, $_POST['stud_mname']);
    $stud_lname = mysqli_real_escape_string($db, $_POST['stud_lname']);
    $stud_DOB = date('Y-m-d', strtotime($_POST['stud_DOB']));
    $stud_date_enrolled = date('Y-m-d', strtotime($_POST['stud_date_enrolled']));

    //form validation: ensure that the form is correctly filled...
    //by adding (array_push()) corresponding error unto $errors array
    if (empty($stud_id)) {array_push($errors, "Student Number is Required");}
    if (empty($stud_fname)) {array_push($errors, "First Name is Required");}
    if (empty($stud_mname)) {array_push($errors, "Middle Name is Required");}
    if (empty($stud_lname)) {array_push($errors, "Last Name is Required");}
    if (empty($stud_DOB)) {array_push($errors, "Student Date of Birth is Required");}

    //finally, update student if there are no errors in the form 
    if (count($errors) == 0 ) {
        $query1 =    "UPDATE students SET stud_id='$stud_id', stud_fname='$stud_fname', stud_mname='$stud_mname', stud_lname='$stud_lname', stud_DOB='$stud_DOB', stud_date_enrolled=''$stud_date_enrolled";
        mysqli_query($db, $query1);
        ?>
        <script>
            alert("You successfuly edited a record of Student '$id'");
        </script>
        <?php
        header("Location: database.php");
        exit(0);
    }    

    //SAVE IMAGE
    $imageProcess = 0;
    if (is_array($_FILES)) {
        $fileName = $_FILES['stud_img']['tmp_name'];
        $sourceProperties = getimagesize($fileName);
        $resizeFileName = time();
        $uploadPath = '../studimg/';
        $fileExt = pathinfo($_FILES['stud_img']['name'], PATHINFO_EXTENSION);
        $uploadImageType = $sourceProperties[2];
        $sourceImageWidth = $sourceProperties[0];
        $sourceImageHeight = $sourceProperties[1];
        switch ($uploadImageType) {
            case IMAGETYPE_JPEG:
                $resourceType = imagecreatefromjpeg($fileName);
                $imageLayer = resizeImage($resourceType, $sourceImageWidth, $sourceImageHeight);
                imagejpeg($imageLayer, $uploadPath.$stud_id.$resizeFileName.'.'.$fileExt);
                break;

            case IMAGETYPE_PNG:
                $resourceType = imagecreatefrompng($fileName);
                $imageLayer = resizeImage($resourceType, $sourceImageWidth, $sourceImageHeight);
                imagepng($imageLayer, $uploadPath.$stud_id.$resizeFileName.'.'.$fileExt);
                break;
            
            default:
                $imageProcess = 0; 
                break; 
        }
        $imageProcess = 1;
        $file_name = ($uploadPath. $resizeFileName.".".$fileExt);
    }
    if ($imageProcess == 1) {
        $insert = $db->query("INSERT into stud_image (stud_id, stud_img, created) VALUES ('$stud_id', '$file_name', NOW())");
        if ($insert) {
            $done = move_uploaded_file($fileName, $uploadPath. $resizeFileName.".".$fileExt);
            if ($done) {
                ?>
                <script>
                    alert("Image has been successfully resize and uploaded");
                </script>
                <?php
            }
        }
        
    } else {
        ?>
        <script>
            alert("Invalid Image");
        </script>
        <?php
    }
    $imageProcess = 0;

}
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://kit.fontawesome.com/0db0d33132.js" crossorigin="anonymous"></script>
        <title>Update</title>
        <link rel="stylesheet" type="text/css" href="edit.css">

    </head>

    <body>
        <div class="container">
            <div class = "navigation">
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
            </div>
            
            <section class="main">
                <div class="main-top">
                    <h1>
                    <a href="database.php"><i class="fas fa-chevron-left" style="margin-right: 5px;"></i></a>
                    Update Student Information</h1>
                </div>
                <br>    
                <div class="sec1" style="backgrund-color=white;">
                    <?php 
                    if (isset($_GET['stud_id'])) {
                        $id =  mysqli_real_escape_string($db, $_GET['stud_id']);
                        $query = "SELECT * FROM students WHERE stud_id='$id' ";
                        $query_run = mysqli_query($db, $query);

                        $stud_fname ="";
                        $stud_mname ="";
                        $stud_lname ="";
                        $stud_DOB ="";  
                        $stud_date_enrolled ="";
                        if(mysqli_num_rows($query_run) > 0) {
                            $row = mysqli_fetch_array($query_run);
                            ?>
                            <form method="post"  action = "code.php">
                                <input type="hidden" name="id" value="<?= $row['stud_id']; ?>">
                                <label for="student_name">Upload Image</label><br><br>
                                    <input id="inp_img" class="input-group" name="stud_img" type="file" value="" required><br> <br>
                                 <label for="">Student Name</label><br>
                                    <input type="text" id="" name="stud_fname" placeholder="First Name" value="<?php echo $stud_fname;?>" required>
                                    <input type="text" id="" name="stud_mname" placeholder="Middle Name" value="<?php echo $stud_mname;?>" required>
                                    <input type="text" id="" name="stud_lname" placeholder="Last Name" value="<?php echo $stud_lname;?>" required><br><br>
                                <label for="">Date of Birth</label><br>
                                    <input type="date" class=" input-group" id="DOB" name="stud_DOB" required><br> <br>
                                <label for="">Date Enrolled</label><br>
                                    <input type="date" class=" input-group" id="date_enrolled" name="stud_date_enrolled" required><br> <br>
                                <br><br>
                                <button type="submit" class="submit-btn" name="update_student" >Update Record</button>
                            </form> 
                            <?php
                        } else {
                            echo "<h4> No Such ID  Found</h4>";
                        }           
                    }
                    ?>
                </section>
            </section>
        </div>
    </body>
</html>