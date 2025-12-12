<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '') {
    header("Location: settings.php?error=1");
    exit();
}

// Update user info
if ($password !== '') {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET name=?, password=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $password, $_SESSION['user']['id']);
} else {
    $stmt = $conn->prepare("UPDATE users SET name=? WHERE id=?");
    $stmt->bind_param("si", $username, $_SESSION['user']['id']);
}

$stmt->execute();

// Update session name
$_SESSION['user']['name'] = $username;

header("Location: settings.php?saved=1");
exit();
?>
