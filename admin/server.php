<?php


//connect to database
$db = mysqli_connect('localhost', 'root', 'xoxad', 'project');

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die;
}



//fetch-data in program table 
    $query ="SELECT prog_name FROM program";
    $result = $db->query($query);
    if($result->num_rows> 0){
        $options= mysqli_fetch_all($result, MYSQLI_ASSOC);
    }


//fetch-data in program table 
    $query1 ="SELECT course_code, prog FROM course";
    $result1 = $db->query($query1);
    if($result1->num_rows> 0){
        $options1= mysqli_fetch_all($result1, MYSQLI_ASSOC);
    }

//fetch-data in yandsec table 
    $query2 ="SELECT DISTINCT(section) FROM yandsec";
    $result2 = $db->query($query2);
    if($result2->num_rows> 0){
        $options2= mysqli_fetch_all($result2, MYSQLI_ASSOC);
    }




?>
