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

<?php include('server.php'); //connect to server
      require_once('../phpqrcode/qrlib.php');
      include('errors.php');
      include('success.php');

//initializing variables
$stud_id ="";
$stud_fname ="";
$stud_mname ="";
$stud_lname ="";
$stud_DOB ="";  
$stud_date_enrolled ="";
$stud_prog ="";
$stud_course ="";
$stud_sec ="";
$class_id = "";
$errors = array();
$success = array();

//File upload path 
$targetDir = "uploads/";
   
//QR path 
$path = '../studqr/';


//ADD STUDENT 
if ((isset($_POST['add_student'])) && !empty($_FILES["stud_img"]["name"])) {
    //receive all input values from the form
    $stud_id = mysqli_real_escape_string($db, $_POST['stud_id']);
    $stud_fname = mysqli_real_escape_string($db, $_POST['stud_fname']);
    $stud_mname = mysqli_real_escape_string($db, $_POST['stud_mname']);
    $stud_lname = mysqli_real_escape_string($db, $_POST['stud_lname']);
    $stud_DOB = date('Y-m-d', strtotime($_POST['stud_DOB']));
    $stud_date_enrolled = date('Y-m-d', strtotime($_POST['stud_date_enrolled']));
    $stud_prog = mysqli_real_escape_string($db, $_POST['stud_prog']);
    $stud_course = mysqli_real_escape_string($db, $_POST['stud_course']);
    $stud_sec = mysqli_real_escape_string($db, $_POST['stud_sec']);
    
    //form validation: ensure that the form is correctly filled...
    //by adding (array_push()) corresponding error unto $errors array
    if (empty($stud_id)) {array_push($errors, "Student Number is Required");}
    if (empty($stud_fname)) {array_push($errors, "First Name is Required");}
    if (empty($stud_lname)) {array_push($errors, "Last Name is Required");}
    if (empty($stud_DOB)) {array_push($errors, "Student Date of Birth is Required");}
    if (empty($stud_date_enrolled)) {array_push($errors, "Please Assign Course");}
    if (empty($stud_prog)) {array_push($errors, "Please Assign Section");}
    if (empty($stud_course)) {array_push($errors, "Please Assign Course");}
    if (empty($stud_sec)) {array_push($errors, "Please Assign Section");}

    //------------cheeck if student exist  
    $student_check_query = "SELECT * FROM students WHERE stud_id='$stud_id' AND stud_lname='$stud_lname' LIMIT 1";
    $result1 = mysqli_query($db, $student_check_query);
    $student = mysqli_fetch_assoc($result1);

    if ($student) { //exists
        if ($student['stud_id']==$stud_id) {
            array_push($errors, "Student already exists");
        }
    }

    //finally, register student if there are no errors in the form 
    if (count($errors) == 0 ) {
        $query1 =    "INSERT INTO students (stud_id, stud_fname, stud_mname, stud_lname, stud_DOB, stud_date_enrolled)
                    VALUES ('$stud_id', '$stud_fname', '$stud_mname', '$stud_lname', '$stud_DOB', '$stud_date_enrolled')";
        mysqli_query($db, $query1);
        $_SESSION['success'] = "You successfully added Student";
        $_SESSION['stud_id'] = $stud_id; 
    }
    
    //------------check if student class exits 
    $class_check_query = "SELECT * FROM stud_class WHERE stud_id='$stud_id' AND stud_prog='$stud_prog' AND stud_course='$stud_course' AND stud_sec='$stud_sec'  LIMIT 1";
    $result3 = mysqli_query($db, $class_check_query);
    $class = mysqli_fetch_assoc($result3);

    if ($class) { //exists
        if ($class['stud_id']==$stud_id) {
            array_push($errors, "Student already exists in ", $class_id);
        }
    }

    //finally, register user if there are no errors in the form 
    if (count($errors) == 0 ) {
        $query3 =    "INSERT INTO stud_class (stud_id, stud_prog, stud_course, stud_sec)
                    VALUES ('$stud_id','$stud_prog', '$stud_course', '$stud_sec')";
        mysqli_query($db, $query3);
        $_SESSION['success'] = "You successfully added Student in a class";
        $_SESSION['stud_id'] = $class_id; 
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

    //GENERATE QR 
    //name
    $qrcode = $path.time().".png";
    $qrimage = time().".png";

    //check if qr exist
    $qr_check_query = "SELECT * FROM qrcode WHERE stud_id='$stud_id' LIMIT 1";
    $result = mysqli_query($db, $qr_check_query);
    $qr = mysqli_fetch_assoc($result);

    if ($qr) { //exists
        if ($qr['stud_id']==$stud_id) {
            ?>
            <script>
                alert("QR Code already exist");
            </script>
            <?php
        }
    } 
    if (count($errors) == 0 ) {
        $stud_id = $_REQUEST['stud_id'];
        $query = mysqli_query($db, "INSERT INTO qrcode SET stud_id='$stud_id', qrimage='$qrimage'");
        if ($query) {
            ?>
            <script>
                alert("QR Code has been successfully generated");
            </script>
            <?php
        }
    }
    
    QRcode :: png($stud_id, $qrcode, 'H',10 , 10); 
    //echo "<img src='".$qrcode."'>"; //display QR

}


?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://kit.fontawesome.com/0db0d33132.js" crossorigin="anonymous"></script>
        <title>Student</title>
        <link rel="stylesheet" type="text/css" href="style.css">

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
                    <h1>Add Student</h1>
                </div>
                <br>    
                <div class="sec1">
                    <form method="post" id="students" class="input-group" enctype="multipart/form-data"  action = "">
                        <label for="student_name">Upload Image</label><br><br>
                            <input id="inp_img" class="input-group" name="stud_img" type="file" value="" required><br> <br>
                        <label for="student_id">Student Number</label><br> 
                            <input type="text" id="teacher" name="stud_id" placeholder="ID number" value="<?php echo $stud_id;?>" required><br>
                        <label for="student_name">Student Name</label><br>
                            <input type="text" id="" name="stud_fname" placeholder="First Name" value="<?php echo $stud_fname;?>" required>
                            <input type="text" id="" name="stud_mname" placeholder="Middle Name (NOTE: Leave it blank if you have no middle name)" value="<?php echo $stud_mname;?>">
                            <input type="text" id="" name="stud_lname" placeholder="Last Name" value="<?php echo $stud_lname;?>" required><br><br>
                        <label for="">Date of Birth</label><br>
                            <input type="date" class=" input-group" id="DOB" name="stud_DOB" required><br> <br>
                        <label for="">Date Enrolled</label><br>
                            <input type="date" class=" input-group" id="date_enrolled" name="stud_date_enrolled" required><br> <br>
                        <label for="students">Program</label><br>
                            <select name="stud_prog">
                                <?php 
                                foreach ($options as $option) {
                                ?>
                                    <option> <?php echo $option['prog_name']; ?> </option>
                                    <?php 
                                    }
                                ?>
                            </select>
                        <label for="teacher">Course</label><br>
                            <select name="stud_course">
                                <?php 
                                foreach ($options1 as $option1) {
                                ?>
                                    <option><?php  echo $option1['course_code']; ?> </option>
                                    <?php 
                                    }
                                ?>
                            </select>
                        <label for="teachers">Section</label><br>
                            <select name="stud_sec">
                                <?php 
                                foreach ($options2 as $option2) {
                                ?>
                                    <option><?php echo $option2['section']; ?> </option>
                                    <?php 
                                    }
                                ?> 
                            </select>
                        <br><br>
                        <button type="submit" class="submit-btn" name="add_student" >Submit</button>
                    </form> 

                    <?php
                        function resizeImage($resourceType, $image_width, $image_height) {
                            $resizeWidth = 500;
                            $resizeHeight = 500;
                            $imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
                            imagecopyresampled($imageLayer, $resourceType, 0,0,0,0, $resizeWidth, $resizeHeight, $image_width, $image_height);
                            return $imageLayer;
                        }
                    ?>
                    
                    <?php /*
                        // Get images from the database
                        $query = $db->query("SELECT * FROM stud_image ORDER BY created DESC");

                        if($query->num_rows > 0){
                            while($row = $query->fetch_assoc()){
                                $imageURL = 'uploads/'.$row["file_name"];
                        ?>
                            <img src="<?php echo $imageURL; ?>" alt="" />
                        <?php }
                        }else{ ?>
                            <p>No image(s) found...</p>
                        <?php } */
                    ?>
                
                    <!------------<script  type="text/javascript">
                        function fileChange(e) {
                            document.getElementById('inp_img').value = '';
                            var file = e.target.fies[0];
                            if (file.type == "image/jpeg" || file.type == "image/png") {
                                var reader = new FileReader();
                                reader.onload = function(rederEvent) {
                                    var image = new Image(); 
                                    image.onload = function(imageEvent) {
                                        var max_size = 400;
                                        var w = image.width;
                                        var h = image.height;

                                        if (w > h) {
                                            if (w > max_size) {
                                                h*=max_size/w;
                                                w=max_size;
                                            } 
                                        } else {
                                            if (h > max_size) {
                                                w*=max_size/h; 
                                                h=max_size; 
                                            }
                                        }
                                        var canvas = document.createElement('canvas');
                                        canvas.width = w;
                                        canvas.height = h;
                                        canvas.getContext('2d').drawImage(image, 0,0,w,h);

                                        if (file.type == "image/jpeg") {
                                            var deataURL = canvas.toDataURL("image/jpeg", 1.0);
                                        } else {
                                            var deataURL = canvas.toDataURL("image/jpeg");
                                        }
                                        document.getElementById('inp_img').value = dataURL;
                                    }
                                    image.src = readerEvent.target.result;
                                }
                                reader.readAsDataURL(file);
                            } else {
                                document.getElementbyId('inp_file').value = '';
                                alert('Please only select images in JPG- or PNG-format')
                            }       
                        }
                        document.getElementbyId('inp_file').addEventListener('change', fileChange, false);
                    </script>-->
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
                                        <th>Image</th>
                                        <th>Birthday</th>
                                        <th>Date Enrolled</th>
                                        <th>Class</th>
                                    </tr>
                                </thead>
                                <tbody> <br>
                                    <?php 
                                        $query = "SELECT DISTINCT students.stud_id, stud_fname, stud_mname, stud_lname, stud_DOB, stud_date_enrolled, stud_image.stud_img, stud_class.stud_prog, stud_class.stud_course, stud_class.stud_sec
                                        FROM students
                                        INNER JOIN stud_image
                                        ON students.stud_id = stud_image.stud_id
                                        INNER JOIN stud_class
                                        ON students.stud_id = stud_class.stud_id
                                        ORDER BY students.date_created DESC";
                                        $query_run = mysqli_query($db, $query);
                                        $space = " ";
                                        $dash = " - ";
                                        $comma = ", ";

                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach($query_run as $row) {
                                    ?>
                                        <tr>
                                            <?php 
                                            echo "<td>".$row['stud_id']."</td>";
                                            echo "<td>".$row['stud_lname'], $comma, $row['stud_fname'], $space, $row['stud_mname']."</td>";
                                            echo "<td>" . "<img src=".$row['stud_img'].' width=100px height="100px">' . "</td>";
                                            echo "<td>".$row['stud_DOB']."</td>";
                                            echo "<td>".$row['stud_date_enrolled']."</td>";
                                            echo "<td>".$row['stud_prog'], $dash, $row['stud_course'], $dash, $row['stud_sec']."</td>";
                                            ?>
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