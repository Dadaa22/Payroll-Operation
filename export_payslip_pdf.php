<?php
require "db.php";
require 'vendor/autoload.php'; // make sure dompdf is installed via composer

use Dompdf\Dompdf;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Missing payslip ID for PDF.");
}
$payroll_id = intval($_GET['id']);
if ($payroll_id <= 0) die("Invalid payslip ID.");

// fetch same data as pay.php
$sql = "
    SELECT
        p.*,
        e.fullname,
        e.daily_rate AS employee_daily_rate,
        e.department,
        e.tax_number,
        e.bank_account
    FROM payroll p
    LEFT JOIN employees e ON p.employee_id = e.id
    WHERE p.payroll_id = ?
";
$stmt = $conn->prepare($sql);
if (!$stmt) die("Prepare failed: " . $conn->error);
$stmt->bind_param("i", $payroll_id);
$stmt->execute();
$res = $stmt->get_result();
$pay = $res->fetch_assoc();
if (!$pay) die("Payslip not found.");

// prepare HTML for PDF (simple, A4 portrait)
$employee_name = htmlspecialchars($pay['fullname'] ?? "Employee #".$pay['employee_id']);
$dept = htmlspecialchars($pay['department'] ?? '-');
$paydate = htmlspecialchars($pay['payroll_date'] ?? '');
$basic = number_format((float)($pay['basic_pay'] ?? 0),2);
$gross = number_format((float)($pay['gross_income'] ?? 0),2);
$ded = number_format((float)($pay['total_deductions'] ?? 0),2);
$net = number_format((float)($pay['net_pay'] ?? 0),2);
$allow = number_format((float)($pay['allowance'] ?? 0),2);
$cash_adv = number_format((float)($pay['cash_advance'] ?? 0),2);

$html = '
<!doctype html>
<html>
<head>
<meta charset="utf-8"/>
<style>
  body { font-family: DejaVu Sans, Arial, sans-serif; margin: 30px; color: #000; }
  .header { text-align:center; }
  .company { font-size:18px; font-weight:bold; }
  .sub { font-size:12px; margin-bottom:20px; }
  .card { border:1px solid #333; padding:16px; border-radius:6px; }
  table { width:100%; border-collapse:collapse; margin-top:10px; }
  th, td { padding:8px; border-bottom:1px solid #ddd; text-align:left; }
  th { background:#f4f4f4; }
  .right { text-align:right; }
  .total { font-weight:bold; font-size:16px; }
</style>
</head>
<body>
  <div class="header">
    <div class="company">Sam Hiranand Corporate Group</div>
    <div class="sub">Chowking Vermosa — Payslip</div>
  </div>

  <div class="card">
    <table>
      <tr><th>Employee</th><td>'.$employee_name.'</td><th>Payroll Date</th><td>'.$paydate.'</td></tr>
      <tr><th>Department</th><td>'.$dept.'</td><th>Days Worked</th><td>'.htmlspecialchars($pay['days_worked'] ?? '').'</td></tr>
    </table>

    <h4>Earnings</h4>
    <table>
      <tr><th>Description</th><th class="right">Amount (₱)</th></tr>
      <tr><td>Basic Pay</td><td class="right">'.$basic.'</td></tr>
      <tr><td>Allowance</td><td class="right">'.$allow.'</td></tr>
      <tr><td class="total">Gross Income</td><td class="right total">'.$gross.'</td></tr>
    </table>

    <h4>Deductions</h4>
    <table>
      <tr><th>Description</th><th class="right">Amount (₱)</th></tr>
      <tr><td>Cash Advance</td><td class="right">'.$cash_adv.'</td></tr>
      <tr><td class="total">Total Deductions</td><td class="right total">'.$ded.'</td></tr>
    </table>

    <h3 style="text-align:right;">NET PAY: ₱'.$net.'</h3>
  </div>
</body>
</html>
';

// instantiate and render
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("payslip_{$payroll_id}.pdf", ["Attachment" => true]);
exit;
