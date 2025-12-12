<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// ----------------------------------------
// Fix 1: DomPDF Options
// ----------------------------------------
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);

// ----------------------------------------
// Employees Dataset
// ----------------------------------------
$employees = [
    ['name'=>'Keith Caballero','basic'=>12000,'overtime'=>1500,'special'=>2000,'night'=>1200],
    ['name'=>'Alysah Calandada','basic'=>11000,'overtime'=>1200,'special'=>1800,'night'=>1000],
    ['name'=>'Mike Pontigon','basic'=>13000,'overtime'=>2000,'special'=>1500,'night'=>900],
    ['name'=>'Daryl Sumalde','basic'=>10500,'overtime'=>900,'special'=>1600,'night'=>800],
    ['name'=>'Joy Ocampo','basic'=>11500,'overtime'=>1400,'special'=>1700,'night'=>1100],
];

$index = $_GET['index'] ?? 0;
$emp = $employees[$index];

// Compute Total
$total = $emp['basic'] + $emp['overtime'] + $emp['special'] + $emp['night'];

// ----------------------------------------
// Fixed HTML with UTF-8 support
// ----------------------------------------
$html = '
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; }
.box { 
    border: 1px solid #333; 
    padding: 20px; 
    width: 450px; 
    margin: 20px auto; 
}
h2 { text-align: center; }
h3 { text-align: center; }
</style>

<div class="box">
    <h2>Employee Payslip</h2>

    <p><b>Name:</b> '.$emp['name'].'</p>
    <hr>

    <p>Basic Pay: ₱'.number_format($emp['basic']).'</p>
    <p>Overtime: ₱'.number_format($emp['overtime']).'</p>
    <p>Special Day: ₱'.number_format($emp['special']).'</p>
    <p>Night Diff: ₱'.number_format($emp['night']).'</p>

    <hr>
    <h3>Total: ₱'.number_format($total).'</h3>
</div>
';

// ----------------------------------------
// Fix 2: Prevent corrupted output
// ----------------------------------------
if (ob_get_length()) { 
    ob_end_clean(); 
}

// ----------------------------------------
// Generate PDF
// ----------------------------------------
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("payslip.pdf", ["Attachment" => true]);
