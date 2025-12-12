<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Employees data
$employees = [
    [
        'name' => 'Keith Caballero',
        'avatar' => 'keith.jpg',
        'basic' => 12000,
        'overtime' => 1500,
        'special' => 2000,
        'night' => 1200
    ],
    [
        'name' => 'Alysah Calandada',
        'avatar' => 'aly.jpg',
        'basic' => 11000,
        'overtime' => 1200,
        'special' => 1800,
        'night' => 1000
    ],
    [
        'name' => 'Mike Pontigon',
        'avatar' => 'mike.jpg',
        'basic' => 13000,
        'overtime' => 2000,
        'special' => 1500,
        'night' => 900
    ],
    [
        'name' => 'Daryl Sumalde',
        'avatar' => 'daryl.jpg',
        'basic' => 10500,
        'overtime' => 900,
        'special' => 1600,
        'night' => 800
    ],
    [
        'name' => 'Joy Ocampo',
        'avatar' => 'joy.jpg',
        'basic' => 11500,
        'overtime' => 1400,
        'special' => 1700,
        'night' => 1100
    ]
];

// Index handling
$index = isset($_GET['index']) ? (int)$_GET['index'] : 0;
$totalEmployees = count($employees);

if ($index < 0) $index = 0;
if ($index >= $totalEmployees) $index = 0;

// Search override
$search = strtolower($_GET['search'] ?? '');
if ($search) {
    foreach ($employees as $i => $emp) {
        if (strpos(strtolower($emp['name']), $search) !== false) {
            $index = $i;
            break;
        }
    }
}

$emp = $employees[$index];
$total = $emp['basic'] + $emp['overtime'] + $emp['special'] + $emp['night'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: url('chicken.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            color: white;
            padding-bottom: 120px;
        }

        .header-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .search-box {
            background: white;
            padding: 6px 12px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 10px auto 20px;
            max-width: 400px;
        }

        .search-box input {
            border: none;
            outline: none;
            width: 100%;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2px solid black;
            object-fit: cover;
        }

        .chart-box {
            background: white;
            border-radius: 15px;
            padding: 15px;
            max-width: 400px;
            margin: 0 auto;
        }

        canvas {
            max-width: 220px !important;
            max-height: 220px !important;
            margin: auto;
            display: block;
        }

        .card-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
            color: black;
            max-width: 400px;
            margin: 20px auto;
        }

        .btn-nav {
            background: black;
            color: white;
            padding: 12px 35px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: 0.25s ease;
        }

        .btn-nav:hover {
            background: #333;
            transform: scale(1.05);
        }

        .button-bar {
            max-width: 400px;
            margin: 10px auto 30px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<div class="container p-3">

  

    <!-- TITLE -->
    <h3 class="text-center mt-3">Employee Reports</h3>

    <!-- SEARCH -->
    <form method="GET">
        <div class="search-box">
            <input type="text" name="search" placeholder="Search employee..."
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" style="border:none;background:none;">🔍</button>
        </div>
    </form>

    <!-- PROFILE -->
    <div class="text-center mt-3">
        <img src="<?= htmlspecialchars($emp['avatar']) ?>"
             onerror="this.src='jabee.jpg';"
             class="avatar">
        <h5 class="mt-2"><?= htmlspecialchars($emp['name']) ?></h5>
    </div>

    <!-- PIE GRAPH -->
    <div class="chart-box mt-4">
        <canvas id="payChart"></canvas>
    </div>

    <!-- EARNING DETAILS -->
    <div class="card-box">
        <h5 class="text-center fw-bold">Earning Details</h5>

        <div class="d-flex justify-content-between mt-3">
            <span>Basic Pay</span>
            <span>₱<?= number_format($emp['basic']) ?></span>
        </div>

        <div class="d-flex justify-content-between mt-2">
            <span>Overtime</span>
            <span>₱<?= number_format($emp['overtime']) ?></span>
        </div>

        <div class="d-flex justify-content-between mt-2">
            <span>Special Day</span>
            <span>₱<?= number_format($emp['special']) ?></span>
        </div>

        <div class="d-flex justify-content-between mt-2">
            <span>Night Diff</span>
            <span>₱<?= number_format($emp['night']) ?></span>
        </div>

        <hr>

        <div class="d-flex justify-content-between fw-bold">
            <span>Total Earnings</span>
            <span>₱<?= number_format($total) ?></span>
        </div>
    </div>

    <!-- BUTTONS -->
    <div class="button-bar">
        <!-- Back to Dashboard -->
        <a href="dashboard.php" class="btn-nav">⬅ Back</a>

        <!-- Next Employee -->
        <a href="reports.php?index=<?= ($index + 1) % $totalEmployees ?>" class="btn-nav">
            Next ➜
        </a>
    </div>

</div>

<!-- CHART JS -->
<script>
const ctx = document.getElementById('payChart').getContext('2d');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Basic Pay', 'Overtime', 'Special Day', 'Night Diff'],
        datasets: [{
            data: [
                <?= $emp['basic'] ?>,
                <?= $emp['overtime'] ?>,
                <?= $emp['special'] ?>,
                <?= $emp['night'] ?>
            ],
            backgroundColor: ['#000000', '#f1c40f', '#e67e22', '#e74c3c']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>

</body>
</html>