<?php 
include 'connection.php';

mysqli_query($con,"DELETE FROM request WHERE req_code='".$_POST['cid']."'");
?>