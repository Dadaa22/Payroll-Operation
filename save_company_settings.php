<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$company_name   = trim($_POST['company_name'] ?? '');
$company_branch = trim($_POST['company_branch'] ?? '');
$company_values = trim($_POST['company_values'] ?? '');

$stmt = $conn->prepare("
    UPDATE company_settings 
    SET name=?, branch=?, values_text=? 
    WHERE id=1
");
$stmt->bind_param("sss", $company_name, $company_branch, $company_values);
$stmt->execute();

header("Location: settings.php?saved=1");
exit();
?>
