<?php
session_start();

//initializing variables
$teacher_id= "";
$username = "";
$email = ""; 
$password = "";
$password_1 = "";
$password_2 = "";
$errors = array();

//connect to database
$db = mysqli_connect('localhost', 'root', 'xoxad', 'project');

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//REGISTER USER 
if (isset($_POST['reg_user'])) {
    //receive all input values from the form
    $teacher_id = mysqli_real_escape_string($db, $_POST['teacher_id']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
    
    //form validation: ensure that the form is correctly filled...
    //by adding (array_push()) corresponding error unto $errors array
    if (empty($teacher_id)) {array_push($errors, "Teacher ID is required");}
    if (empty($username)) {array_push($errors, "Username is required");}
    if (empty($email)) {array_push($errors, "Email is required");}
    if (empty($password_1)) {array_push($errors, "Password is required");}
    if ($password_1 != $password_2) {
        array_push($errors, "Password does not match");
    }

    //first check if teacher is officially registered 
    $teacher_check_query = "SELECT * FROM teachers WHERE teacher_id = '$teacher_id' LIMIT 1";
    $res = mysqli_query($db, $teacher_check_query);
    $teacher = mysqli_fetch_assoc($res);

    if ($teacher) {
        if ($teacher['teacher_id']!=$teacher_id) {
            array_push($errors, "Please Report to Admin for Registration");
            ?> 
            <script> alert("Please Report to Admin for Registration"); </script>
            <?php
        } else {
            //first check the database to make sure 
            //a user does not actually exist with the same username and/or email
            $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' OR teacher_id = '$teacher_id' LIMIT 1";
            $result = mysqli_query($db, $user_check_query);
            $user = mysqli_fetch_assoc($result);
        
            if ($user) { //exists
            
                if ($user['username']==$username) {
                    array_push($errors, "Username already exists");
                }
                if ($user['email']==$email) {
                    array_push($errors, "Email already exists");
                }
            }
        }
    }


    //finally, register user if there are no errors in the form 
    if (count($errors) == 0 ) {
        $password = md5($password_1); //password encryption before saving in the database
        $query =    "INSERT INTO users (teacher_id, username, email, password)
                    VALUES ('$teacher_id', '$username', '$email', '$password')";
        mysqli_query($db, $query);
        $row = mysqli_fetch_array($result);
        
        $_SESSION['username'] = $username; 
        $_SESSION['success'] = "You are now logged in";
        header('location: ../prof/dashboard.php');
    }
}

//LOGIN USER 
if (isset($_POST['login_user'])) {
    $time=time()-30;
    $ip_address=getIpAddr();
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    
    $query1=mysqli_query($db,"select count(*) as total_count from loginlogs where TryTime > $time and IpAddress='$ip_address'");
    $check_login_row=mysqli_fetch_assoc($query1);
    $total_count=$check_login_row['total_count'];

    if(empty($username)) {
        array_push($errors, "Username is required", $username);
    }
    if(empty($password)) {
        array_push($errors, "Password is required");
    }
    if($total_count==3){
        array_push($errors, "To many failed login attempts. Please login after 30 sec");
    } else {
    // Coding for login
        if (count($errors) == 0) {
            $password = md5($password); //decrypting password
            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $results = mysqli_query($db, $query);
            $row = mysqli_fetch_array($results);
    
            if (mysqli_num_rows($results)==1) {
                if($row["usertype"]=="user") {
                    $userid=$_SESSION['user']['teacher_id'];
                    echo $_SESSION['username'] = $username; 
                    echo $_SESSION['success'] = "You are now logged in";
                    header('location: ../prof/dashboard.php');
                } 
                if($row["usertype"]=="admin") {
                    echo $_SESSION['username'] = $username; 
                    echo $_SESSION['success'] = "You are now logged in";
                    header('location: ../admin/dashboard.php');
                }
                mysqli_query($db,"delete from loginlogs where IpAddress='$ip_address'");
                echo "<script>window.location.href='dashboard.php';</script>";
            } else {
                array_push($errors, "Wrong username or password combination");
                $total_count++;
                $rem_attm=3-$total_count;
                if($rem_attm==0){
                    array_push($errors, "To many failed login attempts. Please login after 30 sec");
                }else{
                    array_push($errors, "Please enter valid login details.<br/>$rem_attm attempts remaining");
                }
                $try_time=time();
                mysqli_query($db,"insert into loginlogs(IpAddress,TryTime) values('$ip_address','$try_time')");
            }
        }
    }
}

// Getting IP Address
function getIpAddr(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
    $ipAddr=$_SERVER['HTTP_CLIENT_IP'];
    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $ipAddr=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
    $ipAddr=$_SERVER['REMOTE_ADDR'];
    }
    return $ipAddr;
    }

?>
