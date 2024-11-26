<?php
include'../connection.php';

if($_POST['p_id']==""){
	mysqli_query($con,"INSERT into unit(unitDesc) values ('".$_POST['unit']."')");
	echo "1";
}
else{
	mysqli_query($con,"UPDATE unit set unitDesc='".$_POST['unit']."' WHERE uid='".$_POST['p_id']."'");
	echo "2";
}
?>