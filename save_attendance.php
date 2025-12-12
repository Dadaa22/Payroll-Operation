<?php
include 'includes/db.php';

$employee_id = $_POST['employee_id'];
$date       = $_POST['date'];
$time_in    = $_POST['time_in'];
$break_out  = $_POST['break_out'];
$break_in   = $_POST['break_in'];
$time_out   = $_POST['time_out'];

$sql = "INSERT INTO attendance (employee_id, date, time_in, break_out, break_in, time_out)
        VALUES ('$employee_id', '$date', '$time_in', '$break_out', '$break_in', '$time_out')";

mysqli_query($conn, $sql);


header("Location: attendance.php");
exit();
?>
