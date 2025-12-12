<?php
$conn = new mysqli("localhost", "root", "", "payroll_app");

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $date = $_POST['date'];
    $total_salary = $_POST['total_salary'];

    $sql = "INSERT INTO payroll_summary (payroll_date, total_salary)
            VALUES ('$date', '$total_salary')";

    if ($conn->query($sql)) {
        echo "Saved Successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
