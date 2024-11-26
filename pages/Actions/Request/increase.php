<?php
include'../connection.php';

mysqli_query($con,"UPDATE temprequest set qty=qty + 1 WHERE tempid='".$_POST['cid']."'");

?>