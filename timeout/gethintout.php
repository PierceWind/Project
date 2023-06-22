<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<?php
require ('../prof/server.php');

// get the q parameter from URL
$id2 = $_REQUEST["id2"];
$course  = $_REQUEST["course"]; // kinuha ko yung GET parameter nito mula sa xhr. sa attendance.php
$now = date('Y-m-d');  

if($course !="") {
  if ($id2 !="") { //check if id exist 
    $id2 = addslashes($_REQUEST['id2']);
    $result=mysqli_query($db,"SELECT * FROM stud_class WHERE stud_id='$id2' AND stud_course= '$course'");
    $rowcount=mysqli_num_rows($result);
    if($rowcount==1){ //check if student is part of the class
      $chk = mysqli_query($db,"SELECT * FROM timein WHERE stud_id='$id2' AND log_date='$now'");
      $cnt = mysqli_num_rows($chk);
      if ($cnt<=1) { // check if student timed in 
        $check= mysqli_query($db,"SELECT * FROM timeout WHERE stud_id='$id2' AND log_date='$now'");
        $count=mysqli_num_rows($check);
        if ($count==0) { //check if the student has already timed out 
          $ret=mysqli_query($db,"INSERT INTO timeout (stud_id, course_code, log_date ,log_time) VALUES ('$id2', '$course', NOW(), NOW())");
          if($ret) { //check if timed out is stored
            $up = mysqli_query($db,"UPDATE timein SET `status`='Present' WHERE stud_id = '$id2' AND log_date='$now' AND course_code='$course'");
            $query = "SELECT DISTINCT stud_img FROM stud_image WHERE stud_id='$id2'";
            $query_run = mysqli_query($db, $query);
            if (mysqli_num_rows($query_run) > 0) {
              foreach($query_run as $row) {
                echo "<img src=".$row['stud_img'].' class="pic" style=" width: 200px; float: center; position: sticky;>';
              }
            }
            ?> <label style="float: center;" for="status"><br><br>Status</label><br> <?php echo '<div class="alert alert-success" style="float: center; "><strong>Success! </strong> active student successfully timed out </div>';
            echo date('l'); ?> <br> <?php
            echo date (' jS \of F Y '); ?> <br> <?php
            echo date ('h:i:s A');
          } 
        } else {
          $query = "SELECT DISTINCT stud_img FROM stud_image WHERE stud_id='$id2'";
          $query_run = mysqli_query($db, $query);
          if (mysqli_num_rows($query_run) > 0) {
            foreach($query_run as $row) {
              echo "<img src=".$row['stud_img'].' class="pic" style=" width: 200px; float: center; position: sticky;>';
            }
          }
          ?> <label style="float: center;" for="status"><br><br>Status</label><br> <?php echo '<div class="alert alert-failed" style="float: center; "><strong> Student has already timed out</strong></div>';
        }
      } else {
          $query = "SELECT DISTINCT stud_img FROM stud_image WHERE stud_id='$id2'";
          $query_run = mysqli_query($db, $query);
          if (mysqli_num_rows($query_run) > 0) {
            foreach($query_run as $row) {
              echo "<img src=".$row['stud_img'].' class="pic" style=" width: 200px; float: center; position: sticky;>';
            }
          }
        ?> <label style="float: center;" for="status"><br><br>Status</label><br> <?php echo '<div class="alert alert-failed" style="float: center; "><strong> Student has no record of time in</strong></div>';
      }
    } else {
      ?> <label style="float: center;" for="status">Status</label><br> <?php echo '<div class="alert alert-failed" style="float: center; "><strong>Failed! </strong> Student is not Officially Enrolled </div>';
    }
  } else {
    ?> <label for="status">Status</label><br> <?php echo '<div class="alert alert-failed"><strong>Failed!</strong> Student is not part of the Class </div>' + date('l jS \of F Y h:i:s A');
      ?>
      <?php 
        
      
  }
}

?>