<?php
session_start();
require "db.php";
require "compute_payroll.php";

// Load employees for dropdown
$emp = mysqli_query($conn, "SELECT id, fullname, daily_rate FROM employees ORDER BY fullname ASC");
if (!$emp) {
    die("Error loading employees: " . mysqli_error($conn));
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // sanitize / cast incoming POST data
    $employee_id     = isset($_POST['employee_id']) ? (int)$_POST['employee_id'] : 0;
    $daily_rate      = isset($_POST['daily_rate']) ? (float)$_POST['daily_rate'] : 0.0;
    $payroll_date    = isset($_POST['payroll_date']) ? $_POST['payroll_date'] : null;
    $days_worked     = isset($_POST['days_worked']) ? (int)$_POST['days_worked'] : 0;
    $overtime_hours  = isset($_POST['overtime_hours']) ? (float)$_POST['overtime_hours'] : 0.0;
    $night_hours     = isset($_POST['night_hours']) ? (float)$_POST['night_hours'] : 0.0;
    $regular_holiday = isset($_POST['regular_holiday']) ? (int)$_POST['regular_holiday'] : 0;
    $special_holiday = isset($_POST['special_holiday']) ? (int)$_POST['special_holiday'] : 0;
    $allowance       = isset($_POST['allowance']) ? (float)$_POST['allowance'] : 0.0;
    $cash_advance    = isset($_POST['cash_advance']) ? (float)$_POST['cash_advance'] : 0.0;

    // COMPUTE PAYROLL
    $result = computePayroll(
        $daily_rate,
        $days_worked,
        $overtime_hours,
        $night_hours,
        $regular_holiday,
        $special_holiday,
        $allowance,
        $cash_advance
    );

    // Safely extract computed values (use defaults if keys missing)
    $basic_pay        = isset($result['basic_pay']) ? (float)$result['basic_pay'] : 0.0;
    $gross_income     = isset($result['gross_income']) ? (float)$result['gross_income'] : 0.0;
    // Accept either 'deductions' or 'total_deductions' or 'total_deduct'
    if (isset($result['deductions'])) {
        $total_deductions = (float)$result['deductions'];
    } elseif (isset($result['total_deductions'])) {
        $total_deductions = (float)$result['total_deductions'];
    } elseif (isset($result['total_deduct'])) {
        $total_deductions = (float)$result['total_deduct'];
    } else {
        $total_deductions = 0.0;
    }
    $net_pay = isset($result['net_pay']) ? (float)$result['net_pay'] : ($gross_income - $total_deductions);

    // Prepare INSERT
    $sql = "
        INSERT INTO payroll (
            employee_id, payroll_date, days_worked,
            overtime_hours, night_hours,
            regular_holiday, special_holiday,
            allowance, cash_advance,
            basic_pay, gross_income, total_deductions, net_pay
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // show DB prepare error for debugging
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    // types: i (employee_id), s (payroll_date), i (days_worked),
    // d (overtime_hours), d (night_hours), i (regular_holiday), i (special_holiday),
    // d (allowance), d (cash_advance),
    // d (basic_pay), d (gross_income), d (total_deductions), d (net_pay)
    $types = "isiddiidddddd"; // 13 types matching 13 values

    $bind_ok = $stmt->bind_param(
        $types,
        $employee_id,
        $payroll_date,
        $days_worked,
        $overtime_hours,
        $night_hours,
        $regular_holiday,
        $special_holiday,
        $allowance,
        $cash_advance,
        $basic_pay,
        $gross_income,
        $total_deductions,
        $net_pay
    );

    if (!$bind_ok) {
        die("bind_param failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    if ($stmt->execute()) {
        header("Location: payroll_summary.php?success=1");
        exit();
    } else {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Payroll</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #8B0000; }
    .header-bar {
        background-color: black;
        padding: 15px;
        color: white;
        text-align: center;
        border-radius: 5px;
        margin-bottom: 25px;
    }
    .header-bar img { height: 60px; }
    .card-custom { border: 3px solid black; }
</style>
</head>

<body>

<div class="container mt-4">

    <div class="header-bar shadow">
        <img src="jabee.jpg" alt="Logo">
        <h3 class="m-0">Sam Hiranand Corporate Group</h3>
        <p class="m-0">Chowking Vermosa</p>
    </div>

    <div class="col-lg-6 mx-auto">
        <div class="card card-custom shadow">

            <div class="card-header bg-dark text-white text-center">
                <h4>Compute Payroll</h4>
            </div>

            <div class="card-body bg-light">
                <form method="POST">

                    <!-- EMPLOYEE DROPDOWN -->
                    <label class="form-label fw-bold">Employee</label>
                    <select name="employee_id" id="employee_id" class="form-select mb-3" required>
                        <option value="">-- Select Employee --</option>
                        <?php while ($e = mysqli_fetch_assoc($emp)): ?>
                            <option value="<?= $e['id'] ?>" data-rate="<?= $e['daily_rate'] ?>">
                                <?= htmlspecialchars($e['fullname']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label class="form-label fw-bold">Daily Rate</label>
                    <input type="number" name="daily_rate" id="daily_rate" class="form-control mb-3" required>

                    <label class="form-label fw-bold">Payroll Date</label>
                    <input type="date" name="payroll_date" class="form-control mb-3" required>

                    <label class="form-label fw-bold">Days Worked</label>
                    <input type="number" name="days_worked" class="form-control mb-3" required>

                    <label class="form-label fw-bold">Overtime Hours</label>
                    <input type="number" step="0.01" name="overtime_hours" class="form-control mb-3">

                    <label class="form-label fw-bold">Night Diff Hours</label>
                    <input type="number" step="0.01" name="night_hours" class="form-control mb-3">

                    <label class="form-label fw-bold">Regular Holidays</label>
                    <input type="number" name="regular_holiday" class="form-control mb-3">

                    <label class="form-label fw-bold">Special Holidays</label>
                    <input type="number" name="special_holiday" class="form-control mb-3">

                    <label class="form-label fw-bold">Allowance</label>
                    <input type="number" step="0.01" name="allowance" class="form-control mb-3">

                    <label class="form-label fw-bold">Cash Advance</label>
                    <input type="number" step="0.01" name="cash_advance" class="form-control mb-3">

                    <button class="btn btn-dark w-100">Compute & Save</button>
                </form>

            </div>

        </div>
    </div>

</div>

<script>
// Autofill daily rate when selecting employee
document.getElementById('employee_id').addEventListener('change', function () {
    let rate = this.options[this.selectedIndex].getAttribute('data-rate');
    document.getElementById('daily_rate').value = rate || '';
});
</script>

</body>
</html>
