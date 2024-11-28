<?php

include '../Actions/connection.php'; // Ensure this file correctly connects to your database




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $department = trim($_POST['department']);
    $position = trim($_POST['position']);

    // Prepare the SQL statement
    $stmt = $con->prepare("INSERT INTO user (name, email, password, department, position) VALUES (?, ?, ?, ?, ?)");

    // Check if prepare() failed
    if ($stmt === false) {
        die("Error preparing statement: " . $con->error);
    }

    $stmt->bind_param("sssss", $name, $email, $password, $department, $position);

    if ($stmt->execute()) {
        echo "Registration successful. <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SJCB Boutique IMRS - Registration</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Source Sans Pro', sans-serif;
      background-color: #f8f9fa;
    }
    .container {
      max-width: 500px;
      margin-top: 50px;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    .card-header {
      background-color: #007bff;
      color: #ffffff;
      text-align: center;
      padding: 20px;
      font-size: 28px;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .card-body {
      padding: 20px 30px;
    }
    .form-control {
      border-radius: 8px;
      font-size: 14px;
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      width: 100%;
    }
    .btn-primary:hover {
      background-color: #0056b3;
    }
    .footer-link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }
    .footer-link a {
      color: #007bff;
      text-decoration: none;
    }
    .footer-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <div>SJCB Boutique IMRS</div>
        <small style="font-size: 14px; font-weight: 300; display: block; margin-top: 5px;">Integrated Management Registration System</small>
      </div>
      <div class="card-body">
        <form action="register.php" method="post">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
          </div>
          <div class="form-group">
            <label for="department">Department</label>
            <input type="text" class="form-control" id="department" name="department" placeholder="Enter your department">
          </div>
          <div class="form-group">
            <label for="position">Position</label>
            <input type="text" class="form-control" id="position" name="position" placeholder="Enter your position">
          </div>
          <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <div class="footer-link">
          <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
