<?php
require 'connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {
    // Nhận thông tin từ form
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $age = $_POST["age"];
    $nationality = mysqli_real_escape_string($conn, $_POST["nationality"]);
    $passport = mysqli_real_escape_string($conn, $_POST["passport"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);
    $hotel = mysqli_real_escape_string($conn, $_POST["hotel"]);

    // Kiểm tra nếu email đã tồn tại trong bảng user
    $user_query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");

    if (mysqli_num_rows($user_query) > 0) {
        // Nếu người dùng đã có tài khoản, lấy userID
        $user = mysqli_fetch_assoc($user_query);
        $userID = $user['userID'];
    } else {
        // Nếu không tìm thấy email, cần xử lý hoặc báo lỗi cho người dùng
        echo "Email không tồn tại trong hệ thống.";
        exit();
    }
    // Kiểm tra nếu người dùng đã đăng ký cuộc đua này
    $check_query = "SELECT * FROM participation WHERE userID = '$userID' AND marathonID = '$marathonID'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Nếu đã đăng ký, thông báo lỗi
        echo "Bạn đã đăng ký cuộc đua này. Không thể đăng ký lại.";
        exit();
    }
    // Đăng ký vào bảng participation, để entryNo là NULL
    $insert_query = "INSERT INTO participation (userID, marathonID, entryNo, hotel, timeRecord, passport, phone, address)
                     VALUES ('$userID', '2', NULL, '$hotel', NULL, '$passport', '$phone', '$address')";

    if (mysqli_query($conn, $insert_query)) {
        // Nếu thành công, hiển thị thông báo thành công
        echo "Đăng ký thành công.";
    } else {
        // Nếu có lỗi, hiển thị lỗi
        echo "Lỗi khi đăng ký: " . mysqli_error($conn);
    }
}

// Hiển thị thông báo đăng ký và entryNo nếu có
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    // Truy vấn để lấy entryNo từ bảng participation
    $query = "SELECT entryNo FROM participation WHERE userID = '$userID' AND marathonID = 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc(  $result);

    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Registration Success</title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
</head>
<body>
    <div class='container mt-4'>
        <h1 class='text-center'>Registration Successful</h1>";

    if ($row && $row['entryNo']) {
        echo "<p class='text-center'>Your entry number is: " . htmlspecialchars($row['entryNo']) . "</p>";
    } else {
        echo "<p class='text-center'>Your registration is successful. Your entry number will be assigned by admin. Please check back later.</p>";
    }

    echo "<div class='text-center'>
            <a href='profile.php' class='btn btn-primary'>Go to Profile</a>
          </div>
    </div>
</body>
</html>";
    exit();
}
?>
