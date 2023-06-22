<?php


//connect to database
$db = mysqli_connect('localhost', 'root', 'xoxad', 'project');

if (mysqli_connect_errno()) {
    
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//fetch-data in yandsec table 
$query2 ="SELECT DISTINCT(year) FROM yandsec ORDER BY year ASC";
$result2 = $db->query($query2);
if($result2->num_rows> 0){
    $options2= mysqli_fetch_all($result2, MYSQLI_ASSOC);
}

?>



