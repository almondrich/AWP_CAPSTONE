<?php
include'../connection.php';

mysqli_query($con,"DELETE FROM suppliers WHERE supid='".$_POST['cid']."'");

?>