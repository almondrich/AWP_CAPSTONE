<?php
session_start();
include 'connection.php';
$q=mysqli_query($con,"SELECT * FROM security WHERE user='".$_POST['username']."' and pass='".$_POST['password']."'");
$rows=mysqli_fetch_array($q);

if(mysqli_num_rows($q)>0){
	$_SESSION['account']=$rows[0];
	if($rows[2]=='Administrator'){
		echo "1";
	}
	else{
		echo "2";
	}
		
}
else{
	echo "0";
}
?>