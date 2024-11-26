<?php
include'../connection.php';

mysqli_query($con,"DELETE FROM tempstockin WHERE tempid='".$_POST['cid']."'");

?>