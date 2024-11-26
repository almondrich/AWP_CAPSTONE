<?php
include'../connection.php';

mysqli_query($con,"DELETE FROM employees WHERE eid='".$_POST['cid']."'");

?>