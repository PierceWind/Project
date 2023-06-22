<?php 

require('server.php');

$userid = $_SESSION['username'];

//fetch-data in program table 
$query ="SELECT assigned_course 
FROM teachers 
INNER JOIN users 
ON users.teacher_id = teachers.teacher_id 
WHERE users.username = '$userid'";
$result = $db->query($query);
if($result->num_rows> 0){
    $options= mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>