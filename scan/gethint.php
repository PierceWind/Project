<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<?php
require ('server.php');

// get the q parameter from URL
$id = $_REQUEST["id"];
$now = date('Y-m-d');

//$hint = "";

// lookup all hints from array if $q is different from ""
if ($id !="") {
  $result=mysqli_query($db,"SELECT * FROM students WHERE stud_id='$id'");
  $rowcount=mysqli_num_rows($result);
  if($rowcount==1){
    $check= mysqli_query($db,"SELECT * FROM entrance_log WHERE stud_id='$id' AND logdate='$now'");
    $count=mysqli_num_rows($check);
    if ($count==0) {  
      $ret="INSERT INTO entrance_log VALUES ('$id',NOW(), NOW())";
      $result = mysqli_query($db,$ret);
      if($result) {
        $query = "SELECT DISTINCT stud_img FROM stud_image WHERE stud_id='$id'";
        $query_run = mysqli_query($db, $query);
        if (mysqli_num_rows($query_run) > 0) {
          foreach($query_run as $row) {
            echo "<img src=".$row['stud_img'].' class="pic" style=" width: 200px; float: center; position: sticky;>';
          }
        }
        ?> <br> <label style="float: center;" for="status"><br> <br> Status</label><br> <?php echo '<div class="alert alert-success" style="float: center; "><strong>Success! </strong> active student successfully timed in </div>';
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
        ?> <label style="float: center;" for="status"> <br> <br>Status</label><br> <?php echo '<div class="alert alert-failed" style="float: center; "><strong>Failed! </strong> Attendance is not stored </div>';
      }
    } else {
      $query = "SELECT DISTINCT stud_img FROM stud_image WHERE stud_id='$id'";
      $query_run = mysqli_query($db, $query);
      if (mysqli_num_rows($query_run) > 0) {
        foreach($query_run as $row) {
          echo "<img src=".$row['stud_img'].' class="pic" style=" width: 200px; float: center; position: sticky;>';
        }
      }
      ?> <br> <br> <label style="float: center;" for="status"> <br> <br>Status</label><br> <?php echo '<div class="alert alert-failed" style="float: center; "><strong> Student has already timed in </strong></div>';
    }
    
  } else {
    ?> <label style="float: center;" for="status">Status</label><br> <?php echo '<div class="alert alert-failed" style="float: center; "><strong>Failed! </strong> Student is not Officially Enrolled </div>';
  }
} else {
  ?> <label for="status">Status</label><br> <?php echo '<div class="alert alert-failed"><strong>Failed!</strong> QR Code does not exist </div>' + date('l jS \of F Y h:i:s A');
    ?>
    <?php 
      
    
}

// Output "no suggestion" if no hint was found or output correct values
//echo $hint === "" ? "no suggestion" : $hint;
?>