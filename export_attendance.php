<?php
include 'db.php';

// Force download as Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=attendance_export.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Start Excel table
echo "<table border='1'>";

echo "
<tr style='font-weight:bold; background:#d3d3d3;'>
    <th>Employee</th>
    <th>Date</th>
    <th>Time In</th>
    <th>Break Out</th>
    <th>Break In</th>
    <th>Time Out</th>
</tr>
";

// Fetch attendance with employee name
$query = "
    SELECT a.*, e.fullname
    FROM attendance a
    LEFT JOIN employees e ON a.employee_id = e.id
    ORDER BY a.date DESC
";

$result = mysqli_query($conn, $query);

// Output each row
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>". htmlspecialchars($row['fullname']) ."</td>";
    echo "<td>". $row['date'] ."</td>";
    echo "<td>". $row['time_in'] ."</td>";
    echo "<td>". $row['break_out'] ."</td>";
    echo "<td>". $row['break_in'] ."</td>";
    echo "<td>". $row['time_out'] ."</td>";
    echo "</tr>";
}

echo "</table>";
