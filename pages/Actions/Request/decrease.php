<?php
include'../connection.php';

mysqli_query($con,"UPDATE temprequest set qty=qty - 1 WHERE tempid='".$_POST['cid']."'");

$q=mysqli_query($con,"SELECT * FROM temprequest WHERE tempid='".$_POST['cid']."'");
$rows=mysqli_fetch_array($q);

if($rows[3]==0){
	mysqli_query($con,"DELETE FROM temprequest WHERE tempid='".$_POST['cid']."'");
}

?>