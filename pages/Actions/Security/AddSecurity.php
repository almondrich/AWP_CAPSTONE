<?php
include'../connection.php';

if($_POST['p_id']==""){
	mysqli_query($con,"INSERT into security values ('".$_POST['username']."','".$_POST['password']."','User','".$_POST['fullname']."')");
	echo "1";
}
else{
	mysqli_query($con,"UPDATE security set pass='".$_POST['password']."', fullname='".$_POST['fullname']."' WHERE user='".$_POST['p_id']."'");
	echo "2";
}
?>