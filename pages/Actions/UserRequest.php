<?php 
include 'connection.php';
mysqli_query($con,"UPDATE request set status='1' WHERE req_code='".$_POST['cid']."'");
?>