<?php
session_start();
require "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Accept id from URL (strict)
if (!isset($_GET['id']) || $_GET['id'] === '') {
    die("Missing payslip ID. Open this page using pay.php?id=PAYROLL_ID");
}
$payroll_id = intval($_GET['id']);
if ($payroll_id <= 0) {
    die("Invalid payslip ID.");
}

// Select payroll and employee fields - use only existing columns to avoid SQL errors
$sql = "
    SELECT
        p.*,
        e.fullname,
        e.daily_rate AS employee_daily_rate,
        e.department,
        e.tax_number,
        e.bank_account,
        e.avatar
    FROM payroll p
    LEFT JOIN employees e ON p.employee_id = e.id
    WHERE p.payroll_id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param("i", $payroll_id);
$stmt->execute();
$result = $stmt->get_result();
$pay = $result->fetch_assoc();
if (!$pay) {
    die("Payslip not found for ID: {$payroll_id}");
}

// Safe fallbacks
$employee_name = !empty($pay['fullname']) ? $pay['fullname'] : "Employee #{$pay['employee_id']}";
$department    = $pay['department'] ?? '-';
$daily_rate    = isset($pay['employee_daily_rate']) ? (float)$pay['employee_daily_rate'] : (float)($pay['daily_rate'] ?? 0);

$days_worked      = (float)($pay['days_worked'] ?? 0);
$overtime_hours   = (float)($pay['overtime_hours'] ?? 0);
$night_hours      = (float)($pay['night_hours'] ?? 0);
$regular_holiday  = (float)($pay['regular_holiday'] ?? 0);
$special_holiday  = (float)($pay['special_holiday'] ?? 0);
$allowance        = (float)($pay['allowance'] ?? 0);
$cash_advance     = (float)($pay['cash_advance'] ?? 0);

$basic_pay        = (float)($pay['basic_pay'] ?? 0);
$gross_income     = (float)($pay['gross_income'] ?? 0);
$total_deductions = (float)($pay['total_deductions'] ?? 0);
$net_pay          = (float)($pay['net_pay'] ?? ($gross_income - $total_deductions));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payslip - <?= htmlspecialchars($employee_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #8B0000; font-family: Arial, sans-serif; color: white; min-height: 100vh; text-align: center; }
        .header-bar { margin-top: 25px; }
        .header-bar img { width: 70px; border-radius: 50%; }
        .avatar { width: 120px; height: 120px; border-radius: 50%; border: 3px solid black; object-fit: cover; margin-top: 15px; background: white; }
        .card-box { background: white; color: black; width: 90%; max-width: 650px; border-radius: 16px; padding: 25px; margin: 30px auto; box-shadow: 0 10px 25px rgba(0,0,0,0.3); text-align: left; }
        .card-title { font-size: 20px; font-weight: bold; margin-bottom: 15px; text-align:center; }
        .btn-nav { background: black; color: white; padding: 10px 20px; border-radius: 30px; text-decoration: none; font-weight: bold; display:inline-block; margin: 6px; }
        .btn-nav:hover { background:#333; }
        .two-col { display:flex; justify-content:space-between; gap:20px; }
        .two-col .col { width:48%; }
        table.table td, table.table th { vertical-align: middle; }
    </style>
</head>
<body>

<div class="header-bar">
    <img src="jabee.jpg" alt="logo">
    <h5 class="mt-2">Sam Hiranand Corporate Group</h5>
    <small>Chowking Vermosa</small>
</div>

<div class="text-center">
    <?php
    $avatar_path = $pay['avatar'] ?? '';
    if ($avatar_path && file_exists($avatar_path)) {
        $avatar_src = $avatar_path;
    } else {
        $avatar_src = 'icon.jpg';
    }
    ?>
    <img src="<?= htmlspecialchars($avatar_src) ?>" class="avatar" alt="avatar">
    <h3 class="mt-2"><?= htmlspecialchars($employee_name) ?></h3>
</div>

<div class="card-box">
    <div class="card-title">PAYSLIP</div>

    <div class="two-col mb-3">
        <div class="col">
            <p><strong>Payroll Date:</strong> <?= htmlspecialchars($pay['payroll_date'] ?? '') ?></p>
            <p><strong>Department:</strong> <?= htmlspecialchars($department) ?></p>
            <p><strong>Daily Rate:</strong> ₱<?= number_format($daily_rate,2) ?></p>
        </div>
        <div class="col">
            <p><strong>Days Worked:</strong> <?= number_format($days_worked,2) ?></p>
            <p><strong>Overtime Hrs:</strong> <?= number_format($overtime_hours,2) ?></p>
            <p><strong>Night Diff Hrs:</strong> <?= number_format($night_hours,2) ?></p>
        </div>
    </div>

    <h6>EARNINGS</h6>
    <table class="table">
        <tr><th>Basic Pay</th><td>₱<?= number_format($basic_pay,2) ?></td></tr>
        <tr><th>Allowance</th><td>₱<?= number_format($allowance,2) ?></td></tr>
        <tr><th>Gross Income</th><td>₱<?= number_format($gross_income,2) ?></td></tr>
    </table>

    <h6>DEDUCTIONS</h6>
    <table class="table">
        <tr><th>Cash Advance</th><td>₱<?= number_format($cash_advance,2) ?></td></tr>
        <tr><th>Total Deductions</th><td>₱<?= number_format($total_deductions,2) ?></td></tr>
    </table>

    <h5 class="text-center">NET PAY: ₱<?= number_format($net_pay,2) ?></h5>

    <div class="text-center mt-3">
        <a href="export_payslip_pdf.php?id=<?= $payroll_id ?>" class="btn-nav" style="background:#c9302c;">Download PDF</a>
        <a href="javascript:window.print();" class="btn-nav" style="background:#0275d8;">Print Payslip</a>
        <a href="payroll_summary.php" class="btn-nav">Back to Summary</a>
    </div>
</div>

</body>
</html>
