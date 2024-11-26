<?php
include'../connection.php';

$q=mysqli_query($con,"SELECT itemDesc,unitDesc from vitems WHERE iid='".$_POST['cid']."'");
$rows=mysqli_fetch_array($q);
echo json_encode($rows);

?>