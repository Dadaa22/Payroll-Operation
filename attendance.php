<?php
include 'db.php';

// Fetch employees list
$empQuery = "SELECT id, fullname FROM employees ORDER BY fullname ASC";
$empResult = mysqli_query($conn, $empQuery);

// Fetch attendance records
$attQuery = "
    SELECT a.*, e.fullname 
    FROM attendance a
    LEFT JOIN employees e ON a.employee_id = e.id
    ORDER BY a.date DESC
";
$attResult = mysqli_query($conn, $attQuery);
?>


<!DOCTYPE html>
<html>
<head>
  <title>Attendance</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body style="background:#8B0000; min-height:100vh; color:white;">

<!-- TOP LOGO -->
<div class="container-fluid p-3 d-flex align-items-center justify-content-center" style="gap:15px;">
  <img src="jabee.jpg" style="width:70px; height:70px; border-radius:50%;">
  <div>
    <h4 style="margin:0;">Sam Hiranand Corporate Group</h4>
    <small>Chowking Vermosa</small>
  </div>
</div>

<div class="container text-center mb-4">
  <h3>Attendance Records</h3>
</div>

<!-- ATTENDANCE FORM -->
<div class="container bg-white text-dark p-4 rounded mb-4">
  <h4 class="text-center mb-3">Add Attendance</h4>

  <form method="POST" action="save_attendance.php" class="row g-3">

    <div class="col-md-6">
      <label>Employee</label>
      <select name="employee_id" class="form-select" required>
        <option value="">Select Employee</option>

        <?php while ($emp = mysqli_fetch_assoc($empResult)): ?>
          <option value="<?= $emp['id'] ?>"><?= $emp['fullname'] ?></option>
        <?php endwhile; ?>

      </select>
    </div>

    <div class="col-md-6">
      <label>Date</label>
      <input type="date" name="date" class="form-control" required>
    </div>

    <div class="col-md-3">
      <label>Time In</label>
      <input type="time" name="time_in" class="form-control" required>
    </div>

    <div class="col-md-3">
      <label>Break Out</label>
      <input type="time" name="break_out" class="form-control">
    </div>

    <div class="col-md-3">
      <label>Break In</label>
      <input type="time" name="break_in" class="form-control">
    </div>

    <div class="col-md-3">
      <label>Time Out</label>
      <input type="time" name="time_out" class="form-control">
    </div>

    <div class="col-12 text-center mt-3">
      <button class="btn btn-dark px-5 rounded-pill">Save Attendance</button>
    </div>

  </form>
</div>

<!-- ATTENDANCE TABLE -->
<div class="container bg-white text-dark p-4 rounded">

  <h4 class="mb-3">Attendance List</h4>

  <table class="table table-bordered text-center">
    <thead class="table-dark">
      <tr>
        <th>Employee</th>
        <th>Date</th>
        <th>Time In</th>
        <th>Break Out</th>
        <th>Break In</th>
        <th>Time Out</th>
      </tr>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_assoc($attResult)): ?>
      <tr>
        <td><?= $row['fullname'] ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['time_in'] ?></td>
        <td><?= $row['break_out'] ?></td>
        <td><?= $row['break_in'] ?></td>
        <td><?= $row['time_out'] ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>

  </table>

</div>

  <div class="container text-center mt-4">
    <a href="export_attendance.php" class="btn btn-dark px-5 rounded-pill">Export to Excel</a>
</div>

<div class="container text-center mt-4">
  <a href="dashboard.php" class="btn btn-dark px-5 rounded-pill">Back to Dashboard</a>
</div>

</body>
</html>
