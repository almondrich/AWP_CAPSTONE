<?php
include'../connection.php';

$q=mysqli_query($con,"SELECT * FROM security WHERE user='".$_POST['cid']."'");
$rows=mysqli_fetch_array($q);
echo json_encode($rows);

?>