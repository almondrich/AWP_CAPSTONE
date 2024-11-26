<?php
include('connection.php');

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$query =mysqli_query($con, "SELECT * FROM vissuance WHERE xDate BETWEEN '$startDate' AND '$endDate'");
while ($row = mysqli_fetch_array($query)) {
    echo '<tr><td>' . $row['0'] . '</td><td>' . $row['1'] . '</td><td>' . $row['2'] . '</td><td>' . $row['3'] . '</td></tr>';
}
?>
