<?php

sleep(1);

include ("server.php");
if(isset($_POST['request'])){

    $request = $_POST['request'];
   
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
    WHERE users.username = '$userid' AND stud_course = '$request'";
    $result = mysqli_query($db, $query);
    $count =  mysqli_num_rows($result);

    
    ?>
    <table class="table">
        <?php
        if($count){
        ?>
        <thead>
            <tr>
                <th> Student Id </th>
                <th> Student Last Name </th>
                <th> Class </th>
                <th> Date </th>
                <th> Status </th>
            </tr>  
            <?php
        }else{
            echo"Sorry no record found";
        }
        ?>
        </thead>

        <tbody>
            <?php
            while($row=mysqli_fetch_assoc($result)){
                
                ?>
                <tr>
                    <td><?= $row['stud_id']; ?></td>
                    <td><?= $row['stud_lname']; ?></td>
                    <td><?= $row['stud_course']; ?> </td>
                    <td><?= $row['log_date']; ?></td>
                    <td><?= $row['status'], $dash, $row['logtime']; ?></td>
                </tr>
                <?php
            }    
                ?>
            
        </tbody>
        
    </table>
    <?php
 
}
?>