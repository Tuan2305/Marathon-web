<?php
require 'connection.php';
include('navbar.php');
session_start();

// Kiểm tra nếu có tham số 'entryNo' trong URL
if (isset($_GET['entryNo']) && !empty($_GET['entryNo'])) {
    $entryNo = $_GET['entryNo'];

    // Truy vấn thông tin từ bảng 'participation' và 'user' dựa trên 'entryNo'
    $query = "SELECT p.entryNo, p.hotel, p.passport, p.phone, p.address, 
                     u.name, u.email
              FROM participation p
              JOIN user u ON p.userID = u.userID
              WHERE p.entryNo = ?";

    // Chuẩn bị câu lệnh SQL
    if ($stmt = mysqli_prepare($conn, $query)) {
        // Liên kết tham số với câu lệnh đã chuẩn bị
        mysqli_stmt_bind_param($stmt, "s", $entryNo);

        // Thực thi câu lệnh
        if (mysqli_stmt_execute($stmt)) {
            // Lấy kết quả truy vấn
            $result = mysqli_stmt_get_result($stmt);

            // Kiểm tra nếu có dữ liệu trả về
            if ($row = mysqli_fetch_assoc($result)) {
                // Hiển thị thông tin người tham gia
                echo "<h2>Registration Details</h2>";
                echo "Name: " . htmlspecialchars($row['name']) . "<br>";
                echo "Email: " . htmlspecialchars($row['email']) . "<br>";
                echo "Entry No: " . htmlspecialchars($row['entryNo']) . "<br>";
                echo "Hotel: " . htmlspecialchars($row['hotel']) . "<br>";
                echo "Passport: " . htmlspecialchars($row['passport']) . "<br>";
                echo "Phone: " . htmlspecialchars($row['phone']) . "<br>";
                echo "Address: " . htmlspecialchars($row['address']) . "<br>";
            } else {
                echo "No registration found for this entry number.";
            }
        } else {
            echo "Error in query execution: " . mysqli_error($conn);
        }

        // Đóng statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    echo "No entry number provided.";
}
?>
