<?php
include'../connection.php';

mysqli_query($con,"UPDATE tempstockin set qty=qty - 1 WHERE tempid='".$_POST['cid']."'");

$q=mysqli_query($con,"SELECT * FROM tempstockin WHERE tempid='".$_POST['cid']."'");
$rows=mysqli_fetch_array($q);

if($rows[3]==0){
	mysqli_query($con,"DELETE FROM tempstockin WHERE tempid='".$_POST['cid']."'");
}

?>