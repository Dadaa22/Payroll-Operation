<?php
require "vendor/autoload.php";
require "compute_payroll.php";
use Dompdf\Dompdf;

// TODO: Replace this with your database query later.
$employees = [
    ['name'=>'Keith Caballero', 'daily_rate'=>600, 'days'=>20, 'ot'=>5, 'night'=>4, 'rh'=>1, 'sh'=>0, 'allowance'=>500, 'cash_advance'=>200],
    ['name'=>'Alysah Calandada', 'daily_rate'=>580, 'days'=>20, 'ot'=>4, 'night'=>2, 'rh'=>0, 'sh'=>1, 'allowance'=>500, 'cash_advance'=>0],
];

$html = "
<h2 style='text-align:center;'>Company Payroll Report</h2>
<table width='100%' border='1' cellspacing='0' cellpadding='6'>
<tr>
<th>Name</th>
<th>Basic Pay</th>
<th>OT</th>
<th>Night Diff</th>
<th>Holiday</th>
<th>Gross</th>
<th>Deductions</th>
<th>Net</th>
</tr>
";

foreach ($employees as $emp) {
    $c = computePayroll(
        $emp['daily_rate'], $emp['days'], $emp['ot'], $emp['night'],
        $emp['rh'], $emp['sh'], $emp['allowance'], $emp['cash_advance']
    );

    $html .= "<tr>
        <td>{$emp['name']}</td>
        <td>₱".number_format($c['basic_pay'])."</td>
        <td>₱".number_format($c['overtime'])."</td>
        <td>₱".number_format($c['night_diff'])."</td>
        <td>₱".number_format($c['holiday_pay'])."</td>
        <td>₱".number_format($c['gross_income'])."</td>
        <td>₱".number_format($c['deductions'])."</td>
        <td><b>₱".number_format($c['net_pay'])."</b></td>
    </tr>";
}

$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "landscape");
$dompdf->render();
$dompdf->stream("Payroll_Report.pdf", ["Attachment"=>true]);
