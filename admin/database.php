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

    if(isset($_POST['delete_student']))
    {
        $id = mysqli_real_escape_string($db, $_POST['delete_student']);
    
        $query = "DELETE FROM students WHERE stud_id='$id'";
        $query_run = mysqli_query($db, $query);
    
        if($query_run) {
            ?>
            <script>
                alert("You successfuly deleted a record of Student <?php 'stud_id' ?> ");
            </script>
            <?php
        }
        else
        {
            ?>
            <script>
                alert("Sorry, Student Record is not Deleted. Please try Again");
            </script>
            <?php
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <title>Database</title>
        <link rel="stylesheet" type="text/css" href="database.css">

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

            <section class="view" id="view">
                <div class="view-list"> <br>
                    <h1>Manage Student Record</h1>                    
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID Number</th>
                                    <th>Image</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Birthday</th>
                                    <th>Date Enrolled</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> <br>
                                <?php 
                                    $query = "SELECT DISTINCT students.stud_id, stud_fname, stud_mname, stud_lname, stud_DOB, stud_date_enrolled, stud_image.stud_img 
                                    FROM students 
                                    INNER JOIN stud_image
                                    ON students.stud_id = stud_image.stud_id
                                    ORDER BY date_created DESC";
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
                                        echo "<td>" . "<img src=".$row['stud_img'].' width=100px height="100px">' . "</td>";
                                        echo "<td>".$row['stud_fname']."</td>";
                                        echo "<td>".$row['stud_mname']."</td>";
                                        echo "<td>".$row['stud_lname']."</td>";
                                        echo "<td>".$row['stud_DOB']."</td>";
                                        echo "<td>".$row['stud_date_enrolled']."</td>";
                                        ?>
                                        <td> 
                                            <a href="edit.php?stud_id=<?= $row['stud_id']; ?>" style="background-color: blue;" class="button">Edit</button></a>   
                                            <form action="" method="POST" class="d-inline">
                                                <button type="submit" style="background-color: red;" value="<?=$row['stud_id'];?>" class="button" name="delete_student">Delete</a>   
                                        </form>                                     
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
        </div>
    </body>
</html>