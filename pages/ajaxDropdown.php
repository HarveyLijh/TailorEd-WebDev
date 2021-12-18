<?php 

include_once 'connection.php'; 
 
if (!empty($_POST["disciplinary"])) { 
       $name = $_POST['disciplinary'];
       $query = "SELECT DISTINCT CLASS_ACAD_ORG_DESCR_COMBO AS department FROM combined WHERE CLASS_ORG_CLUSTER_DESCR_COMBO = '$name' "; 
       $result = mysqli_query($conn ,$query) or die(mysqli_error($conn));

       if($result->num_rows > 0){ 
              echo '<option> </option>'; 
              while($row = mysqli_fetch_assoc($result)) {
                     echo '<option value="'.$row['department'].'">'.$row['department'].'</option>'; 
              } 
       } else { 
              echo '<option value="">Department not available</option>'; 
       } 
} else if (!empty($_POST["department"])) { 
       $name = $_POST['department'];
       $query = "SELECT DISTINCT CLASS_TITLE_COMBO AS course FROM combined WHERE CLASS_ACAD_ORG_DESCR_COMBO = '$name' ";    
       $result = mysqli_query($conn ,$query) or die(mysqli_error($conn));

       if($result->num_rows > 0){ 
              echo '<option> </option>'; 
              while($row = mysqli_fetch_assoc($result)) {
                     echo '<option value=" '.$row['course'].' ">'.$row['course'].'</option>'; 
              } 
       } else { 
              echo '<option value="">Course not available</option>'; 
       } 
} 
?>