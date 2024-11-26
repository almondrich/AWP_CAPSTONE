<?php
include'../connection.php';

	
	$i=mysqli_query($con,"SELECT * FROM items WHERE itemDesc='".$_POST['item']."'");
	$rowsI=mysqli_fetch_array($i);

	$u=mysqli_query($con,"SELECT * FROM unit WHERE unitDesc='".$_POST['unit']."'");
	$rowsU=mysqli_fetch_array($u);

	$countItem=mysqli_query($con,"SELECT * FROM items WHERE itemDesc='".$_POST['item']."' and uid='".$rowsU[0]."'");
	$rowsCountItem=mysqli_fetch_array($countItem);

	if($rowsCountItem > 0){
		$countTempItem=mysqli_query($con,"SELECT * FROM tempstockin WHERE iid='".$rowsI[0]."' and uid='".$rowsU[0]."'");
		$rowsCountTempItem=mysqli_fetch_array($countTempItem);
		if($rowsCountTempItem==0){
			mysqli_query($con,"INSERT into tempstockin(iid,uid,qty) values ('".$rowsI[0]."','".$rowsU[0]."','".$_POST['Quantity']."')");
		}
		else{
		    echo "Meron";
		}
	}
	else{
		echo "Wala";
	}

?>