<?php
include('connection.php');

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$query =mysqli_query($con, "SELECT * FROM vstockin WHERE xDate BETWEEN '$startDate' AND '$endDate'");
// echo '<tr><th>Transaction Code</th><th>Fullname</th><th>Item Name</th><th>Quantity</th></tr>';
//echo json_encode($query);
while ($row = mysqli_fetch_array($query)) {
    echo '<tr><td>' . $row['0'] . '</td><td>' . $row['1'] . '</td><td>' . $row['2'] . '</td><td>' . $row['3'] . '</td></tr>';
}
?>
