<?php
include'../connection.php';

$q=mysqli_query($con,"SELECT * from employees WHERE eid='".$_POST['cid']."'");
$rows=mysqli_fetch_array($q);
echo json_encode($rows);

?>