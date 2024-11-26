<?php
include'../connection.php';

if($_POST['p_id']==""){
	mysqli_query($con,"INSERT into employees values (null,'".$_POST['fullname']."','".$_POST['contact']."','".$_POST['address']."')");
	echo "1";
}
else{
	mysqli_query($con,"UPDATE employees set fullname='".$_POST['fullname']."', contact='".$_POST['contact']."', address='".$_POST['address']."' WHERE eid='".$_POST['p_id']."'");
	echo "2";
}
?>