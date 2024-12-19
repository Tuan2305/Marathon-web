<?php
require 'connection.php';
if (isset($_POST["submit"])) {
  $name = $_POST["username"];
  $nationality = $_POST["nationality"];
  $gender = $_POST["gender"];
  $age = $_POST["age"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirmpassword"];

  $duplicate = mysqli_query($conn, "SELECT * FROM user WHERE name = '$name' OR email = '$email'");
  if (mysqli_num_rows($duplicate) > 0) {
    echo "User already exists";
  } else {
    if ($password == $confirm_password) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $query = "INSERT INTO user (name, nationality, gender, age, email, password) VALUES ('$name', '$nationality', '$gender', '$age', '$email', '$hashed_password')";
      if (mysqli_query($conn, $query)) {
        echo "Registration successful!";
      } else {
        echo "Error: " . mysqli_error($conn);
      }
    } else {
      echo "Passwords do not match!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    body {
      background-color: #f7f7f7;
    }
    .register-container {
      margin-top: 50px;
      padding: 30px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .form-title {
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="container mt-4">
  <div class="row">
    <div class="col-md-6 col-md-offset-3 register-container">
      <h1 class="form-title">Register</h1>
      <form action="" method="POST">
        <div class="form-group">
          <label for="username">Name:</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
          <label for="nationality">Nationality:</label>
          <input type="text" class="form-control" id="nationality" name="nationality" placeholder="Enter your nationality" required>
        </div>
        <div class="form-group">
          <label for="gender">Gender:</label>
          <select class="form-control" id="gender" name="gender" required>
            <option value="">Select your gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="age">Age:</label>
          <input type="number" class="form-control" id="age" name="age" placeholder="Enter your age" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <div class="form-group">
          <label for="confirmpassword">Confirm Password:</label>
          <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Re-enter your password" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
        <div class="text-center">
          <a href="login.php">Already have an account? Login</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>