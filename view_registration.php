<?php
require 'connection.php';
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['userID'])) {
    echo "Vui lòng đăng nhập để xem thông tin đăng ký của bạn.";
    exit;
}

// Lấy thông tin người dùng từ session
$userID = $_SESSION['userID'];

// Truy vấn dữ liệu người dùng từ bảng 'participation' và 'user'
$query = "SELECT p.entryNo, p.hotel, p.passport, p.phone, p.address, 
                 u.name, u.email
          FROM participation p
          JOIN user u ON p.userID = u.userID
          WHERE p.userID = ?";

if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $userID);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Hiển thị thông tin đăng ký
            echo "<h2>Thông tin đăng ký</h2>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
            echo "<p><strong>Entry No:</strong> " . htmlspecialchars($row['entryNo']) . "</p>";
            echo "<p><strong>Hotel:</strong> " . htmlspecialchars($row['hotel']) . "</p>";
            echo "<p><strong>Passport:</strong> " . htmlspecialchars($row['passport']) . "</p>";
            echo "<p><strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
        } else {
            echo "Không tìm thấy thông tin đăng ký.";
        }
    } else {
        echo "Lỗi khi truy vấn cơ sở dữ liệu.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Lỗi khi chuẩn bị câu lệnh SQL.";
}
?>
