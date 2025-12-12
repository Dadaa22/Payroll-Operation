<?php
require "db.php";

// Load payroll data - select payroll_id explicitly to guarantee the field exists
$sql = "
    SELECT 
        p.payroll_id,
        p.employee_id,
        p.payroll_date,
        p.days_worked,
        p.gross_income,
        p.net_pay,
        e.fullname
    FROM payroll p
    LEFT JOIN employees e ON p.employee_id = e.id
    ORDER BY p.payroll_date DESC
";

$query = mysqli_query($conn, $sql);
if (!$query) {
    die("Query error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Payroll Summary</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background-color: #8B0000; }
    .header-bar { background-color: black; padding: 15px; color: white; text-align: center; border-radius: 5px; margin-bottom: 25px; }
    .header-bar img { height: 60px; }
    .card-custom { border: 3px solid black; border-radius: 10px; }
    table th { background-color: black !important; color: white !important; }
    .table td { background-color: #fff; font-weight: 500; }
    .btn-dark { border-radius: 6px; }
</style>
</head>
<body>
<div class="container mt-4">
    <div class="header-bar shadow">
        <img src="jabee.jpg" alt="Logo">
        <h3 class="m-0">Sam Hiranand Corporate Group</h3>
        <p class="m-0">Chowking Vermosa</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success text-center fw-bold">Payroll saved successfully!</div>
    <?php endif; ?>

    <div class="card card-custom shadow">
        <div class="card-header bg-dark text-white text-center">
            <h4 class="m-0">Payroll Summary</h4>
        </div>

        <div class="card-body bg-light">
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Payroll Date</th>
                        <th>Days Worked</th>
                        <th>Gross Income</th>
                        <th>Net Pay</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['fullname'] ?: 'Employee #' . $row['employee_id']) ?></td>
                        <td><?= htmlspecialchars($row['payroll_date']) ?></td>
                        <td><?= htmlspecialchars($row['days_worked']) ?></td>
                        <td><?= number_format((float)$row['gross_income'], 2) ?></td>
                        <td><?= number_format((float)$row['net_pay'], 2) ?></td>
                        <td>
                            <a href="pay.php?id=<?= (int)$row['payroll_id'] ?>" class="btn btn-sm btn-dark">View Payslip</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between mt-3">
                <a href="add_payroll.php" class="btn btn-dark">Add Payroll</a>
                <a href="index.php" class="btn btn-secondary">Home</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
