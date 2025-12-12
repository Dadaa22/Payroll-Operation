<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === "" || $password === "") {
        $message = "Enter email and password!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['name']
                ];
                header("Location: main_screen.php");
                exit();
            } else {
                $message = "Incorrect password!";
            }
        } else {
            $message = "Email not found!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #8B0000;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 0.5rem;
            width: 360px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        .login-title {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #8B0000;
            text-align: center;
        }
        .btn-login {
            background-color: #8B0000;
            border: none;
        }
        .btn-login:hover {
            background-color: #5a0000;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="login-title">LOGIN</h3>

    <?php if ($message != ""): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="Email"
                required
                autofocus
            />
        </div>

        <div class="mb-4">
            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Password"
                required
            />
        </div>

        <button type="submit" class="btn btn-login w-100 text-white fw-bold">
            Log In
        </button>
    </form>
</div>

</body>
</html>
