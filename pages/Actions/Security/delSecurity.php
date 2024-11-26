<?php
include'../connection.php';

mysqli_query($con,"DELETE FROM security WHERE user='".$_POST['cid']."'");

?>