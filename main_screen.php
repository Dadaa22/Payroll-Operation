<!DOCTYPE html>
<html>
<head>
  <title>Main Screen</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      background: url('chicken.jpg') no-repeat center center/cover;
      font-family: Arial, sans-serif;
      position: relative;
      color: white;
    }

    /* Top-left logo + text */
    .top-left {
      position: absolute;
      top: 20px;
      left: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo-circle {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid white;
    }

    .company-text h5 {
      margin: 0;
      font-weight: bold;
    }

    /* Bottom button */
    .start-container {
      position: absolute;
      bottom: 40px;
      width: 100%;
      text-align: center;
    }

    /* Peach-orange button */
    .btn-start {
      background-color: #ffb199;
      color: black;
      font-size: 24px;
      padding: 15px 50px;
      border-radius: 50px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-weight: bold;
    }

    .btn-start:hover {
      background-color: #ff967d;
    }
  </style>
</head>

<body>

  <!-- TOP LEFT LOGO + COMPANY NAME -->
  <div class="top-left">
    <img src="jabee.jpg" class="logo-circle" alt="Logo">
    <div class="company-text">
      <h5>Sam Hiranand Corporate Group</h5>
      <small>Chowking Vermosa</small>
    </div>
  </div>

  <!-- BOTTOM CENTER BUTTON -->
  <div class="start-container">
    <a href="dashboard.php" class="btn-start">
      Get Started <span>➜</span>
    </a>
  </div>

</body>
</html>