<?php
sleep(1);
session_start();
require 'server.php';





//UPDATE STUDENT 
if (isset($_POST['update_student']))  {
    //receive all input values from the form
    $id = mysqli_real_escape_string($db, $_POST['id']);
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
        $query1 =    "UPDATE students SET stud_fname='$stud_fname', stud_mname='$stud_mname', stud_lname='$stud_lname', stud_DOB='$stud_DOB', stud_date_enrolled='$stud_date_enrolled', date_modified=NOW() WHERE stud_id='$id'";
        mysqli_query($db, $query1);
        $_SESSION['success'] = "You successfully updated Student information";
        header("Location: database.php");
        exit(0);
    }    

}
?>