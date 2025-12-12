<?php 
session_start(); 
if (!isset($_SESSION['user'])) { 
    header("Location: index.php"); 
    exit(); 
} 

// Employee data 
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

// Get index for Next button linking
$index = isset($_GET['index']) ? (int)$_GET['index'] : 0;
$total = count($employees);
?> 

<!DOCTYPE html> 
<html> 
<head> 
<title>Payslip</title> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> 

<style> 
body { 
    background: url('chicken.jpg') no-repeat center center/cover; 
    font-family: Arial, sans-serif; 
    color: white; 
    padding-bottom: 120px; 
} 
.header-logo { 
    width: 55px; 
    height: 55px; 
    border-radius: 50%; 
} 
.employee-card { 
    background: #F9A602; 
    border: 2px solid black; 
    border-radius: 10px; 
    padding: 20px; 
    color: black; 
    margin-top: 15px; 
} 
.avatar { 
    width: 110px; 
    height: 110px; 
    border-radius: 50%; 
    object-fit: cover; 
    border: 2px solid black; 
} 
.btn-next { 
    background: black; 
    color: white; 
    border: none; 
    padding: 10px 25px; 
    border-radius: 25px; 
    font-weight: bold; 
    margin-top: 15px; 
    display: inline-block; 
    text-decoration: none; 
} 
.bottom-nav { 
    position: fixed; 
    bottom: 0; 
    left: 0; 
    width: 100%; 
    background: #000; 
    padding: 12px 0; 
    display: flex; 
    justify-content: space-around; 
} 
.bottom-nav a { 
    text-align: center; 
    color: white; 
    text-decoration: none; 
} 
.bottom-nav img { 
    width: 30px; 
} 
</style> 
</head> 

<body> 
<div class="container p-3"> 

    <!-- HEADER --> 
    <div class="text-center mb-2"> 
        <img src="jabee.jpg" class="header-logo"> 
        <h6 class="mt-2">Sam Hiranand Corporate Group</h6>  
        <small>Chowking Vermosa</small> 
    </div> 

    <!-- EMPLOYEE LIST --> 
    <h5 class="mt-3">Employees</h5> 

    <?php foreach($employees as $i => $emp): ?> 
    <div class="employee-card"> 
        <div class="d-flex align-items-center gap-3"> 
            <img src="<?= htmlspecialchars($emp['avatar']) ?>" class="avatar" onerror="this.src='assets/images/avatars/default.png'"> 
            <div> 
                <h5 style="margin:0;"><?= htmlspecialchars($emp['name']) ?></h5> 
                <small><?= htmlspecialchars($emp['position']) ?></small> 
                <div class="mt-2"> 📧 <?= htmlspecialchars($emp['email']) ?> </div> 
                <div class="mt-1"> 📞 <?= htmlspecialchars($emp['phone']) ?> </div> 
            </div> 
        </div> 

        <div class="row mt-4"> 
            <div class="col-6"> 
                <b>Department:</b><br> 
                <?= htmlspecialchars($emp['department']) ?> 
            </div> 
            <div class="col-6"> 
                <b>Hired Date:</b><br> 
                <?= htmlspecialchars($emp['date_hired']) ?> 
            </div> 
        </div> 

    <a href="export_payslip_info_pdf.php?index=<?= $i ?>" class="btn btn-danger mt-2">
   Download PDF
</a>
        <!-- NEXT BUTTON --> 
        <div class="text-end"> 
            <a href="pay.php?index=<?= $i ?>" class="btn-next"> Next </a> 
        </div> 
    </div> 
    <?php endforeach; ?> 

</div> 

<!-- BOTTOM NAVIGATION (UPDATED) --> 
<div class="bottom-nav"> 
    <a href="dashboard.php"> 
        <img src="https://img.icons8.com/ios-filled/50/ffffff/home.png"/> 
        <div>Home</div> 
    </a> 

    <!-- Updated to mess.php --> 
    <a href="mess.php"> 
        <img src="https://img.icons8.com/ios-filled/50/ffffff/task.png"/> 
        <div>Approvals</div> 
    </a> 

    <!-- Updated to mess.php --> 
    <a href="mess.php"> 
        <img src="https://img.icons8.com/ios-filled/50/ffffff/chat.png"/> 
        <div>Message</div> 
    </a> 
</div> 

</body> 
</html>