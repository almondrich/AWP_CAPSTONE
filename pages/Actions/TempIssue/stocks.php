<?php
include'../connection.php';

$q=mysqli_query($con,"SELECT * FROM tempstockin");
$count=mysqli_num_rows($q);


$s=mysqli_query($con,"SELECT * FROM employees WHERE fullname='".$_POST['employee']."'");
$rowsSup=mysqli_fetch_array($s);

if($count>0){

		while($rows=mysqli_fetch_array($q)){
			mysqli_query($con,"INSERT into issuance values (null,'".$_POST['code']."','".$rows[1]."','".$rowsSup[0]."','".$rows[3]."','".$_POST['date']."')");
			mysqli_query($con,"UPDATE items set qty=qty-'".$rows[3]."' WHERE iid='".$rows[1]."'");
		}	
}
mysqli_query($con,"DELETE FROM tempstockin");

?>