<?php
include 'connection.php';
if (isset($_POST['keyword'])) {
     
        $output = '';  
        $result = mysqli_query($con, "SELECT * FROM employees WHERE Fullname LIKE '%".$_POST["keyword"]."%' LIMIT 4");  
        $output = '<ul class="list-unstyled" id="uSL">';  
        
        if(mysqli_num_rows($result) > 0){  
            while($row = mysqli_fetch_array($result)){  
                $output .= '<li id="iSL">'.$row["Fullname"].'</li>';  
            }  
        }else{  
            $output .= '<li id="iSL">User Not Found</li>';  
        }  
    
    $output .= '</ul>';  
    echo $output;  
}
?>