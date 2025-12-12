<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: url('chicken.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            color: white;
            padding-bottom: 120px;
        }

        .menu-btn {
            padding: 18px;
            font-size: 17px;
            font-weight: bold;
            border-radius: 40px;
        }
    </style>
</head>

<body>

<!-- TOP LOGO + NAME -->
<div class="container-fluid p-3 d-flex align-items-center justify-content-center" style="gap:15px;">
    <img 
        src="jabee.jpg" 
        alt="Logo" 
        style="width:70px; height:70px; object-fit:cover; border-radius:50%;"
    >
    <div class="text-center">
        <h4 style="margin:0;">Sam Hiranand Corporate Group</h4>
        <small>Chowking Vermosa</small>
    </div>
</div>

<!-- SALARY BAR GRAPH -->
<div class="container mb-5">
    <div style="background:white; padding:20px; border-radius:15px;">
        <canvas id="salaryChart" height="120"></canvas>
    </div>
</div>

<!-- MAIN BUTTONS -->
<div class="container text-center mb-5">
    <div class="row g-3 justify-content-center">

        <div class="col-6 col-md-4">
            <a href="add_employee.php" class="btn btn-dark menu-btn w-100">Add Employee</a>
        </div>

        <div class="col-6 col-md-4">
            <a href="payslip.php" class="btn btn-dark menu-btn w-100">View Payslip</a>
        </div>

        <div class="col-6 col-md-4">
            <a href="attendance.php" class="btn btn-dark menu-btn w-100">Attendance</a>
        </div>

        <div class="col-6 col-md-4">
            <a href="reports.php" class="btn btn-dark menu-btn w-100">Reports</a>
        </div>

        <div class="col-6 col-md-4">
            <a href="add_payroll.php" class="btn btn-dark menu-btn w-100">Compute Payroll</a>
        </div>

        <!-- EXPORT BUTTONS -->
        <div class="col-6 col-md-4">
            <a href="export_pdf.php" class="btn btn-danger menu-btn w-100">Export Payroll PDF</a>
        </div>

        <div class="col-6 col-md-4">
            <a href="export_excel.php" class="btn btn-success menu-btn w-100">Export Payroll Excel</a>
        </div>

        <div class="col-6 col-md-4">
            <a href="logout.php" class="btn btn-danger menu-btn w-100">Logout</a>
        </div>
        
       <div class="d-flex justify-content-center mt-3">
    <button onclick="window.location.href='settings.php'" 
            class="btn btn-dark px-5 py-3 rounded-pill">
        Settings
    </button>
</div>

    </div>
</div>

<!-- CHART SCRIPT -->
<script>
const ctx = document.getElementById('salaryChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Dec 04', 'Dec 05', 'Dec 06', 'Dec 07', 'Dec 08'],
        datasets: [{
            label: 'Total Company Salary',
            data: [15000, 18000, 16500, 20000, 22000], // replace with dynamic PHP if needed
            backgroundColor: 'black'
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>

</body>
</html>
