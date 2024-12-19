<?php
require 'connection.php'; 
session_start(); 
if (isset($_POST["submit"])) {
  
    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password = '$password'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
      // Kiểm tra mật khẩu
      if ($password == $row["password"]) {
        $_SESSION['userID'] = $row['userID'];
          $_SESSION["login"] = true;
          $_SESSION["id"] = $row["id"];
          $_SESSION["role"] = $row["role"]; // Lưu role vào session

          // Kiểm tra nếu là admin, chuyển hướng tới trang quản trị
          if ($row["role"] == "admin") {
              header("Location: admin_dashboard.php");
          } else {
              header("Location: index.php"); // Trang chính cho người dùng bình thường
          }
          exit();
      } else {
          echo "Invalid password!";
      }
  } else {
      echo "User not found!";
  }
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1 class="text-center">Login</h1>
      <form action="login.php" method="POST">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
      </form>
      <br>
      <p class="text-center">
        Don't have an account? <a href="registration.php">Register here</a>
      </p>
    </div>
  </div>
</div>

</body>
</html>
