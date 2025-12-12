<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// ADD EMPLOYEE
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $position = $_POST['position'];
    $date_hired = $_POST['date_hired'];

    mysqli_query($conn, "INSERT INTO employees (name, email, phone, address, position, date_hired)
    VALUES ('$name', '$email', '$phone', '$address', '$position', '$date_hired')");
}

// DELETE EMPLOYEE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM employees WHERE id = $id");
}

// FETCH EMPLOYEES
$result = mysqli_query($conn, "SELECT * FROM employees");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Employee</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#8B0000; min-height:100vh; color:white;">

<!-- HEADER -->
<div class="container-fluid p-3 text-center">
  <h3>Sam Hiranand Corporate Group</h3>
  <small>Chowking Vermosa</small>
</div>

<!-- FORM -->
<div class="container bg-white text-dark p-4 rounded mb-4">
  <h4 class="mb-3">Add Employee</h4>

  <form method="POST">
    <div class="row g-3">

      <div class="col-md-6">
        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
      </div>

      <div class="col-md-6">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>

      <div class="col-md-6">
        <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
      </div>

      <div class="col-md-6">
        <input type="text" name="address" class="form-control" placeholder="Address" required>
      </div>

      <div class="col-md-6">
        <input type="text" name="position" class="form-control" placeholder="Position" required>
      </div>

      <div class="col-md-6">
        <input type="date" name="date_hired" class="form-control" required>
      </div>
      
      <div class="col-12 text-center mt-3">
        <button type="submit" name="add" class="btn btn-dark px-5 rounded-pill">
          Add Employee
        </button>
      </div>

    </div>
  </form>
</div>

<!-- EMPLOYEE TABLE -->
<div class="container bg-white text-dark p-4 rounded">
  <h4 class="mb-3">Employee List</h4>

  <table class="table table-bordered text-center">
    <thead class="table-dark">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Position</th>
        <th>Date Hired</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['phone']; ?></td>
        <td><?php echo $row['address']; ?></td>
        <td><?php echo $row['position']; ?></td>
        <td><?php echo $row['date_hired']; ?></td>
        <td>
          <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">
            Delete
          </a>
        </td>
      </tr>
      <?php } ?>

    </tbody>
  </table>
</div>

<!-- BACK BUTTON -->
<div class="container text-center mt-4">
  <a href="dashboard.php" class="btn btn-dark px-5 rounded-pill">
    Back to Dashboard
  </a>
</div>

</body>
</html>
