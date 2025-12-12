<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #8B0000;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 40px;
            padding-bottom: 40px;
        }
        .container {
            max-width: 700px;
        }
        .card-header {
            background-color: #8B0000;
            color: white;
            font-weight: 600;
        }
        .btn-dark {
            background-color: #8B0000;
            border: none;
        }
        .btn-dark:hover {
            background-color: #5a0000;
        }
        a.btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            border-radius: 0.3rem;
            transition: background-color 0.3s ease;
        }
        a.btn-secondary:hover {
            background-color: #5a6268;
            color: white;
            text-decoration: none;
        }
        h2.text-center {
            color: #8B0000;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">

    <h2 class="text-center">Settings</h2>

    <!-- User Settings -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            User Settings
        </div>
        <div class="card-body">
            <form action="save_user_settings.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">New Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password" />
                </div>

                <button type="submit" class="btn btn-dark">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Company Settings -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            Company Settings
        </div>
        <div class="card-body">
            <form action="save_company_settings.php" method="POST">
                <div class="mb-3">
                    <label for="company_name" class="form-label fw-semibold">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label for="company_branch" class="form-label fw-semibold">Branch / Location</label>
                    <input type="text" name="company_branch" id="company_branch" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label for="company_values" class="form-label fw-semibold">Moral Values / Motto</label>
                    <textarea name="company_values" id="company_values" class="form-control" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn btn-dark">Save Company Settings</button>
            </form>
        </div>
    </div>

    <div class="text-center">
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

</div>

</body>
</html>
