<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <!-- <script> 
    var course = encodeURIComponent(sessionStorage.getItem('value'));
    
    //document.getElementById('result').innerHTML = course;

    //jQuery.post("attendance.php", {myKey: course}, function(data) { 
      //alert("Do something with example.php response"); 
    //}).fail(function() { 
      //alert("Damn, something broke"); 
    //}); 

    $.ajax({
      url: "gethint.php",
      method: "post",
      data: courses,
      success: function() {HALAAAAA GUMANAAAAA GINAWA MUEEEE??/ DI KO NAKITAAA hahaha
        hahaha, congrats.
      }
    })
  </script> -->
<?php
    require('server.php');

  
// get the parameter from URL
$id = $_REQUEST["id"];
$course  = $_REQUEST["course"]; // kinuha ko yung GET parameter nito mula sa xhr. sa attendance.php
$defstat = 'Cutting';
$now = date('Y-m-d');   

if($course !="") {
  if ($id !="") {
    $id = addslashes($_REQUEST['id']);
    $result=mysqli_query($db,"SELECT * FROM stud_class WHERE stud_id='$id' AND stud_course= '$course' LIMIT 1 ");
    $rowcount=mysqli_num_rows($result); 
    if($rowcount==1){
      $chk = mysqli_query($db,"SELECT DISTINCT * FROM entrance_log WHERE stud_id='$id' AND  logdate='$now'");
      $cnt = mysqli_num_rows($chk);
      if ($cnt==1) {
        $check= mysqli_query($db,"SELECT * FROM timein WHERE log_date='$now'  AND course_code= '$course' AND stud_id='$id'");
        $count=mysqli_num_rows($check);
        if ($count==0) {
          $ret="INSERT INTO timein (stud_id, course_code, status, log_date, log_time) VALUES ('$id', '$course', '$defstat', NOW(), NOW()";
          $result = mysqli_query($db,$ret);
          if($result) {
            $query = "SELECT DISTINCT stud_img FROM stud_image WHERE stud_id='$id'";
            $query_run = mysqli_query($db, $query);
            if (mysqli_num_rows($query_run) > 0) {
              foreach($query_run as $row) {
                echo "<img src=".$row['stud_img'].' class="pic" style=" width: 200px; float: center; position: sticky;>';
              }
            }
            ?> <br> <br> <label style="float: center;" for="status"><br><br>Status</label><br> <?php echo '<div class="alert alert-success" style="float: center; "><strong>Success! </strong> active student successfully timed in </div>';
            echo date('l'); ?> <br> <?php
            echo date (' jS \of F Y '); ?> <br> <?php
            echo date ('h:i:s A');
          } else {
            $query = "SELECT DISTINCT stud_img FROM stud_image WHERE stud_id='$id'";
            $query_run = mysqli_query($db, $query);
            if (mysqli_num_rows($query_run) > 0) {
              foreach($query_run as $row) {
                echo "<img src=".$row['stud_img'].' class="pic" style=" width: 200px; float: center; position: sticky;>';
              }
            }
            ?> <label style="float: center;" for="status"><br><br>Status</label><br> <?php echo '<div class="alert alert-failed" style="float: center; "><strong>Failed! </strong> Attendance is not stored </div>';
          } 
        } else {
          $query = "SELECT DISTINCT stud_img FROM stud_image WHERE stud_id='$id'";
          $query_run = mysqli_query($db, $query);
          if (mysqli_num_rows($query_run) > 0) {
            foreach($query_run as $row) {
              echo "<img src=".$row['stud_img'].' class="pic" style=" width: 200px; float: center; position: sticky;">';
            }
            ?> <br> <br> <label style="float: center;" for="status"><br><br>Status</label><br> <?php echo '<div class="alert alert-success" style="float: center; "><strong> Student has already timed in</strong></div>';
          }
          
        }
      } else {
        $query = "SELECT DISTINCT stud_img FROM stud_image WHERE stud_id='$id'";
        $query_run = mysqli_query($db, $query);
        if (mysqli_num_rows($query_run) > 0) {
          foreach($query_run as $row) {
            echo "<img src=".$row['stud_img'].' class="pic" style=" width: 200px; float: center; position: sticky;">';
          }
        }
        ?> <br> <br> <label style="float: center;" for="status">Status</label><br> <?php echo '<div class="alert alert-failed" style="float: center; "><strong> Student has no record of entry</strong></div>';
      }
      
      
    } else {
      ?> <br> <br> <label style="float: center;" for="status">Status</label><br> <?php echo '<div class="alert alert-failed" style="float: center; "><strong>Failed! </strong> Student is not part of the Class <span id="result"/> </div>';
    }
  } else {
    ?> <label for="status">Status</label><br> <?php echo '<div class="alert alert-failed"><strong>Failed!</strong> QR Code does not exist </div>' + date('l jS \of F Y h:i:s A');
      ?>
      <?php 
        
      
  }
}



// Output "no suggestion" if no hint was found or output correct values
//echo $hint === "" ? "no suggestion" : $hint;
?>