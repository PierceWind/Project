<?php  
//export.php  
session_start();
include ('server.php');
$userid = $_SESSION['username'];

$output = "";
if(isset($_POST["export"]))
{
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
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
        <tr>  
        <th> Student Id </th>
        <th> Student Last Name </th>
        <th> Class </th>
        <th> Date </th>
        <th> Status </th>
        </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
        <tr>  
            <td>'.$row["stud_id"].'</td>  
            <td>'.$row["stud_lname"].'</td>  
            <td>'.$row["stud_course"].'</td>  
            <td>'.$row["log_date"].'</td>  
            <td>'.$row["status"].'</td>
        </tr>
   ';
  }
  $output .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename=attendance.xls');
        echo $output;
    }
}
?>