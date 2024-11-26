<?php
session_start();
include'../connection.php';

$q=mysqli_query($con,"SELECT * FROM temprequest WHERE user='".$_SESSION['account']."'");
$count=mysqli_num_rows($q);

if($count>0){
	while($rows=mysqli_fetch_array($q)){
		mysqli_query($con,"INSERT into request values (null,'".$_POST['code']."','".$_SESSION['account']."','".$rows[2]."','".$rows[3]."','".$rows[4]."','0','".date('Y-m-d')."')");
	}
}
mysqli_query($con,"DELETE FROM temprequest WHERE user='".$_SESSION['account']."'");
?>