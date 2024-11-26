<?php
include'../connection.php';

mysqli_query($con,"DELETE FROM unit WHERE uid='".$_POST['cid']."'");

?>