<?php
include'../connection.php';

mysqli_query($con,"DELETE FROM temprequest WHERE tempid='".$_POST['cid']."'");

?>