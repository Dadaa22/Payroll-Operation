<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Employee list
$employees = [
    ['name' => 'Keith Caballero', 'avatar' => 'keith.jpg'],
    ['name' => 'Alysah Calandada', 'avatar' => 'aly.jpg'],
    ['name' => 'Mike Pontigon', 'avatar' => 'mike.jpg'],
    ['name' => 'Daryl Sumalde', 'avatar' => 'daryl.jpg'],
    ['name' => 'Joy Ocampo', 'avatar' => 'joy.jpg']
];

$total = count($employees);

// SEARCH FUNCTIONALITY
$search = strtolower($_GET['search'] ?? '');
$index = isset($_GET['index']) ? (int)$_GET['index'] : 0;

if ($search !== "") {
    foreach ($employees as $i => $emp) {
        if (strpos(strtolower($emp['name']), $search) !== false) {
            $index = $i;
            break;
        }
    }
}

// Infinite cycle index
$index = ($index + $total) % $total;
$employee = $employees[$index];

// Approval message
$sick_message = "Sir/Ma'am, I am respectfully asking for your approval. I really need this. Thank you po.";

// Handle messages
if (!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = array_fill(0, $total, []);
}

if (!empty($_POST['message'])) {
    $_SESSION['messages'][$index][] = [
        'sender' => 'You',
        'text' => htmlspecialchars($_POST['message']),
        'time' => date('h:i A')
    ];
}

// Handle approvals
if (!isset($_SESSION['approvals'])) {
    $_SESSION['approvals'] = array_fill(0, $total, 'Pending');
}
if (isset($_POST['approve'])) {
    $_SESSION['approvals'][$index] = 'Approved';
}
if (isset($_POST['deny'])) {
    $_SESSION['approvals'][$index] = 'Denied';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages & Approval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('chicken.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            color: white;
            padding-bottom: 120px;
        }
        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2px solid black;
            object-fit: cover;
        }
        .card-box {
            background: white;
            color: black;
            border-radius: 15px;
            padding: 20px;
            max-width: 400px;
            margin: 20px auto;
        }
        .checkbox-group {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 15px 0;
            font-size: 18px;
        }
        .checkbox-group button {
            background: black;
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
            border: none;
        }
        .message-box textarea {
            width: 100%;
            border-radius: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            resize: none;
        }
        .btn-send {
            background: black;
            color: white;
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: bold;
            border: none;
            transition: 0.3s;
        }
        .btn-row {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .btn-small {
            background: black;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            border: none;
            text-decoration: none;
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
        .search-box button {
            border: none;
            background: none;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container p-3">

    <!-- SEARCH BAR -->
    <form method="GET">
        <div class="search-box">
            <input type="text" name="search" placeholder="Search employee..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">🔍</button>
        </div>
    </form>

    <!-- EMPLOYEE INFO -->
    <div class="text-center mt-4">
        <img src="<?= htmlspecialchars($employee['avatar']) ?>" class="avatar">
        <h5 class="mt-2"><?= htmlspecialchars($employee['name']) ?></h5>
    </div>

    <!-- APPROVAL CARD -->
    <div class="card-box">
        <h5 class="text-center fw-bold">Approval</h5>
        <p><strong>Message:</strong></p>
        <p>"<?= $sick_message ?>"</p>
        <p class="text-center"><strong>Status:</strong> <?= $_SESSION['approvals'][$index] ?></p>
        <div class="checkbox-group">
            <form method="POST"><button name="approve">✔ Approve</button></form>
            <form method="POST"><button name="deny">✖ Deny</button></form>
        </div>
    </div>

    <!-- SEND MESSAGE -->
    <div class="card-box">
        <h5 class="text-center fw-bold">Send Message</h5>
        <form method="POST">
            <div class="message-box">
                <textarea rows="4" name="message" placeholder="Type your message here..."></textarea>
            </div>

            <!-- BUTTON ROW -->
            <div class="btn-row">
                <a href="payslip.php" class="btn-small">⬅ Back</a>
                <button class="btn-send" name="send">Send</button>
                <a href="mess.php?index=<?= ($index + 1) % $total ?>" class="btn-small">Next ➜</a>
            </div>
        </form>
    </div>

</div>

</body>
</html>