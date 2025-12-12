<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// ----------------------------------------
// D O M P D F   O P T I O N S
// ----------------------------------------
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);

// ----------------------------------------
// EMPLOYEE DATA (same list as payslip.php)
// ----------------------------------------
$employees = [
    [
        'name' => 'Keith Caballero',
        'position' => 'Cook',
        'email' => 'keith@example.com',
        'phone' => '09123456789',
        'department' => 'Kitchen',
        'date_hired' => '2025-01-10',
        'avatar' => 'keith.jpg'
    ],
    [
        'name' => 'Alysah Calandada',
        'position' => 'Cashier',
        'email' => 'alysah@example.com',
        'phone' => '09567890123',
        'department' => 'Operations',
        'date_hired' => '2025-01-10',
        'avatar' => 'aly.jpg'
    ],
    [
        'name' => 'Mike Pontigon',
        'position' => 'Kitchen Staff',
        'email' => 'mike@example.com',
        'phone' => '09234567890',
        'department' => 'Kitchen',
        'date_hired' => '2025-01-10',
        'avatar' => 'mike.jpg'
    ],
    [
        'name' => 'Daryl Sumalde',
        'position' => 'Crew',
        'email' => 'daryl@example.com',
        'phone' => '09345678901',
        'department' => 'Dining',
        'date_hired' => '2025-01-10',
        'avatar' => 'daryl.jpg'
    ],
    [
        'name' => 'Joy Ocampo',
        'position' => 'Cashier',
        'email' => 'joy@example.com',
        'phone' => '09456789012',
        'department' => 'Operations',
        'date_hired' => '2025-01-10',
        'avatar' => 'joy.jpg'
    ],
];

// ----------------------------------------
// Get employee index
// ----------------------------------------
$index = isset($_GET['index']) ? (int)$_GET['index'] : 0;
$emp = $employees[$index];

// ----------------------------------------
// Avatar handling
// ----------------------------------------
$avatarPath = __DIR__ . "/assets/images/avatars/" . $emp['avatar'];
if (!file_exists($avatarPath)) {
    // fallback image
    $avatarPath = __DIR__ . "/assets/images/avatars/default.png";
}

// Encode image in base64 (works 100% in DomPDF)
$avatarData = base64_encode(file_get_contents($avatarPath));
$avatarBase64 = "data:image/jpeg;base64," . $avatarData;

// ----------------------------------------
// HTML (Clean + Printable)
// ----------------------------------------
$html = '
<meta charset="UTF-8">

<style>
body {
    font-family: Arial, sans-serif;
}
.box {
    width: 500px;
    margin: auto;
    padding: 20px;
    border: 1px solid #000;
}
h2 {
    text-align: center;
    margin-bottom: 20px;
}
.avatar {
    float: right;
    width: 90px;
    height: 90px;
    border-radius: 50%;
}
.info td {
    padding: 6px 4px;
}
</style>

<div class="box">
    <h2>Employee Information</h2>

    <img class="avatar" src="'.$avatarBase64.'">

    <table class="info">
        <tr><td><b>Name:</b></td><td>'.$emp['name'].'</td></tr>
        <tr><td><b>Position:</b></td><td>'.$emp['position'].'</td></tr>
        <tr><td><b>Email:</b></td><td>'.$emp['email'].'</td></tr>
        <tr><td><b>Phone:</b></td><td>'.$emp['phone'].'</td></tr>
        <tr><td><b>Department:</b></td><td>'.$emp['department'].'</td></tr>
        <tr><td><b>Date Hired:</b></td><td>'.$emp['date_hired'].'</td></tr>
    </table>
</div>
';

// ----------------------------------------
// PREVENT PDF CORRUPTION
// ----------------------------------------
if (ob_get_length()) {
    ob_end_clean();
}

// ----------------------------------------
// Render PDF
// ----------------------------------------
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("employee-info.pdf", ["Attachment" => true]);
