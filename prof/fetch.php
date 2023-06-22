<?php

sleep(1);

include ("server.php");
if(isset($_POST['request'])){

    $request = $_POST['request'];
   
    $query = "SELECT DISTINCT students.stud_id, stud_lname, stud_class.stud_prog, stud_class.stud_course, stud_class.stud_sec, yandsec.year, entrance_log.logdate, entrance_log.logtime 
    FROM students
    INNER JOIN stud_class
    ON students.stud_id = stud_class.stud_id
    INNER JOIN yandsec
    ON yandsec.course =  stud_class.stud_course 
    INNER JOIN entrance_log 
    ON students.stud_id = entrance_log.stud_id
    INNER JOIN teachers 
    ON teachers.assigned_course = stud_class.stud_course
    INNER JOIN users 
    ON users.teacher_id = teachers.teacher_id
    WHERE users.username = '$userid' AND  year = '$request'";
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
                <th> Last Name </th>
                <th> Program </th>
                <th> Year </th>
                <th> Time In </th>
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
                    <td><?= $row['stud_prog']; ?> </td>
                    <td><?= $row['year']; ?></td>
                    <td><?= $row['logdate'], $dash, $row['logtime']; ?></td>
                </tr>
                <?php
            }    
                ?>
            
        </tbody>
        
    </table>
    <?php
 
}
?>