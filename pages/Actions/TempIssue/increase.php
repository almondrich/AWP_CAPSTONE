<?php
include'../connection.php';

mysqli_query($con,"UPDATE tempstockin set qty=qty + 1 WHERE tempid='".$_POST['cid']."'");

?>